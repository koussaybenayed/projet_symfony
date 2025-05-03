<?php 
namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Psr\Log\LoggerInterface;

class ForgotPasswordController extends AbstractController
{
    #[Route('/forgot-password', name: 'app_forgot_password')]
    public function forgotPassword(
        Request $request,
        UserRepository $userRepo,
        MailerInterface $mailer,
        SessionInterface $session,
        LoggerInterface $logger = null
    ): Response {
        if ($request->isMethod('POST')) {
            $user_email = $request->request->get('user_email'); // Changed from 'email' to 'user_email'
            $user = $userRepo->findOneBy(['user_email' => $user_email]); // Changed to match entity field

            if ($user) {
                // Generate 6-digit verification code
                $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
                $expiresAt = new \DateTime('+15 minutes');

                // Store in session
                $session->set('reset_code', $code);
                $session->set('reset_code_expires', $expiresAt);
                $session->set('reset_user_id', $user->getUserId());

                try {
                    // Send email with verification code
                    $email = (new Email())
                        ->from('benayedkoussay7@gmail.com')
                        ->to($user->getUserEmail()) // Changed to match your getter method
                        ->subject('Password Reset Verification Code')
                        ->html($this->renderView(
                            'emails/reset_password.html.twig',
                            ['code' => $code]
                        ));

                    $mailer->send($email);
                    $this->addFlash('success', 'Verification code sent to your email.');
                    return $this->redirectToRoute('app_verify_code');
                } catch (TransportExceptionInterface $e) {
                    $logger?->error('Email sending failed: '.$e->getMessage());
                    $this->addFlash('error', 'Failed to send email. Please try again.');
                }
            }

            // Always show success to prevent email enumeration
            $this->addFlash('success', 'If this email exists, you will receive a verification code.');
            return $this->redirectToRoute('app_verify_code');
        }

        return $this->render('user/forgot_password.html.twig');
    }

    #[Route('/verify-code', name: 'app_verify_code')]
    public function verifyCode(
        Request $request,
        SessionInterface $session,
        UserRepository $userRepo,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $em
    ): Response {
        // Check if code verification request
        if ($request->isMethod('POST')) {
            $userCode = $request->request->get('code');
            $newPassword = $request->request->get('password');
            
            // Get stored session data
            $storedCode = $session->get('reset_code');
            $expiresAt = $session->get('reset_code_expires');
            $userId = $session->get('reset_user_id');

            // Validate code
            if (!$storedCode || !$expiresAt || new \DateTime() > $expiresAt || $userCode !== $storedCode) {
                $this->addFlash('error', 'Invalid or expired verification code.');
                return $this->redirectToRoute('app_verify_code');
            }

            // Update password
            $user = $userRepo->find($userId);
            if ($user) {
                $user->setUserPassword($passwordHasher->hashPassword($user, $newPassword));
                $em->flush();

                // Clear session
                $session->remove('reset_code');
                $session->remove('reset_code_expires');
                $session->remove('reset_user_id');

                $this->addFlash('success', 'Password updated successfully!');
                return $this->redirectToRoute('app_login');
            }
        }

        return $this->render('user/verify_code.html.twig');
    }
}