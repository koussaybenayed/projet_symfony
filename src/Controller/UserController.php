<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Entity\User;
use App\Entity\Role;
use App\Form\UserType;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Security;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\HttpFoundation\JsonResponse;
#[Route('/user')]
class UserController extends AbstractController
{ 
    
    #[Route('/profile', name: 'app_user_profile')]
    public function profile(Security $security): Response
    {
        $user = $security->getUser();

        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('user/profilepageuser.html.twig', [
            'user' => $user,
        ]);
    }
    
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }
    
    // Route API avec un préfixe clair ne pouvant pas être confondu avec un ID
    #[Route('/api/search', name: 'app_user_api_search', methods: ['GET'])]
    public function apiSearch(Request $request, UserRepository $userRepository): JsonResponse
    {
        $criteria = $request->query->get('criteria');
        $value = $request->query->get('value');

        $users = [];
        if ($criteria && $value) {
            switch ($criteria) {
                case 'username':
                    $users = $userRepository->findBySearchCriteria($value, null, null);
                    break;
                case 'firstname':
                    $users = $userRepository->findBySearchCriteria(null, $value, null);
                    break;
                case 'lastname':
                    $users = $userRepository->findBySearchCriteria(null, null, $value);
                    break;
            }
        } else {
            $users = $userRepository->findAll();
        }

        $usersArray = [];
        foreach ($users as $user) {
            $usersArray[] = [
                'userId' => $user->getUserId(),
                'userUsername' => $user->getUserUsername(),
                'userEmail' => $user->getUserEmail(),
                'userPassword' => $user->getUserPassword(),
                'userFirstname' => $user->getUserFirstname(),
                'userLastname' => $user->getUserLastname(),
                'userBirthday' => $user->getUserBirthday() ? $user->getUserBirthday()->format('Y-m-d') : null,
                'userGender' => $user->getUserGender(),
                'userPicture' => $user->getUserPicture(),
                'userPhonenumber' => $user->getUserPhonenumber(),
                'userLevel' => $user->getUserLevel(),
                'userRole' => $user->getUserRole()
            ];
        }

        return $this->json($usersArray);
    }
    
    // Puis toutes les autres routes statiques
    #[Route('/stats', name: 'app_user_stats', methods: ['GET'])]
    public function stats(UserRepository $userRepository): Response
    {
        // Get basic statistics
        $totalUsers = $userRepository->count([]);
        $activeUsers = $userRepository->count(['isActive' => true]);
        $inactiveUsers = $totalUsers - $activeUsers;
        
        // Get users by role
        $usersByRole = $userRepository->createQueryBuilder('u')
            ->select('r.role_name, COUNT(u.user_id) as userCount')
            ->join('u.role', 'r')
            ->groupBy('r.role_name')
            ->getQuery()
            ->getResult();
            
        // Get active/inactive ratio
        $activeRatio = $totalUsers > 0 ? round(($activeUsers / $totalUsers) * 100, 2) : 0;
        
        return $this->render('user/stats.html.twig', [
            'totalUsers' => $totalUsers,
            'activeUsers' => $activeUsers,
            'inactiveUsers' => $inactiveUsers,
            'usersByRole' => $usersByRole,
            'activeRatio' => $activeRatio,
        ]);
    }

    #[Route('/search-options', name: 'app_user_search_options', methods: ['GET'])]
    public function searchOptions(UserRepository $userRepository): Response
    {
        // Get all search options from repository
        $options = $userRepository->findAllSearchOptions();
        
        // Return JSON response with options
        return $this->json($options);
    }

    #[Route('/search', name: 'app_user_search', methods: ['POST'])]
    public function search(Request $request, UserRepository $userRepository): Response
    {
        // Get search parameters from request
        $username = $request->request->get('username');
        $firstname = $request->request->get('firstname');
        $lastname = $request->request->get('lastname');
        
        // If no criteria provided, return all users
        if (!$username && !$firstname && !$lastname) {
            $users = $userRepository->findAll();
        } else {
            // Use repository to find matching users
            $users = $userRepository->findBySearchCriteria($username, $firstname, $lastname);
        }
        
        // Convert users to array format for JSON response
        $usersArray = [];
        foreach ($users as $user) {
            $userData = [
                'userId' => $user->getUserId(),
                'userUsername' => $user->getUserUsername(),
                'userEmail' => $user->getUserEmail(),
                'userPassword' => $user->getUserPassword(),
                'userFirstname' => $user->getUserFirstname(),
                'userLastname' => $user->getUserLastname(),
                'userBirthday' => $user->getUserBirthday() ? $user->getUserBirthday()->format('Y-m-d') : null,
                'userGender' => $user->getUserGender(),
                'userPicture' => $user->getUserPicture(),
                'userPhonenumber' => $user->getUserPhonenumber(),
                'userLevel' => $user->getUserLevel(),
                'userRole' => $user->getUserRole()
            ];
            $usersArray[] = $userData;
        }
        
        // Return JSON response
        return $this->json($usersArray);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('user_picture')->getData();
    
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();
    
                try {
                    $imageFile->move(
                        $this->getParameter('pictures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Optionally log the error or show message
                }
    
                $user->setUserPicture($newFilename);
            }
    
            $entityManager->persist($user);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
    #[Route('/newuser', name: 'app_user_neww', methods: ['GET', 'POST'])]

    public function newuser(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('user_picture')->getData();
    
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();
    
                try {
                    $imageFile->move(
                        $this->getParameter('pictures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Optionally log the error or show message
                }
    
                $user->setUserPicture($newFilename);
            }
            $user->setUserRole('user');
            $role = $entityManager->getRepository(Role::class)->find(12);
            if ($role) {
                $user->setRole($role);
            }
            $entityManager->persist($user);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }
    
        return $this->render('user/newuser.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/export-pdf', name: 'app_user_export_pdf', methods: ['GET'])]
    public function exportPdf(UserRepository $userRepository): Response
    {
        // Récupérer les utilisateurs
        $users = $userRepository->findAll();
        $totalUsers = count($users);

        // Configurer Dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        
        // Instancier Dompdf
        $dompdf = new Dompdf($options);
        
        // Générer le HTML
        $html = $this->renderView('user/pdf_users.html.twig', [
            'users' => $users,
            'total_users' => $totalUsers
        ]);
        
        // Charger le HTML dans Dompdf
        $dompdf->loadHtml($html);
        
        // Définir le format du papier (optionnel)
        $dompdf->setPaper('A4', 'portrait');
        
        // Rendre le PDF
        $dompdf->render();
        
        // Générer un nom de fichier
        $filename = 'users_list_' . date('Y-m-d_H-i-s') . '.pdf';
        
        // Envoyer le PDF au navigateur
        return new Response(
            $dompdf->output(),
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"'
            ]
        );
    }

    #[Route('/{user_id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{user_id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
    #[Route('/{user_id}/edit1', name: 'app_user_edit1', methods: ['GET', 'POST'])]
    public function edit1(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_user_profile', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit2.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{user_id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getUserId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }
        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/search-ajax', name: 'app_user_search_ajax', methods: ['GET'])]
    public function searchAjax(Request $request, UserRepository $userRepository): Response
    {
        // Get search parameters from query string
        $criteria = $request->query->get('criteria');
        $value = $request->query->get('value');
        
        // Search users based on selected criteria
        $users = [];
        
        if ($criteria && $value) {
            switch ($criteria) {
                case 'username':
                    $users = $userRepository->findBySearchCriteria($value, null, null);
                    break;
                case 'firstname':
                    $users = $userRepository->findBySearchCriteria(null, $value, null);
                    break;
                case 'lastname':
                    $users = $userRepository->findBySearchCriteria(null, null, $value);
                    break;
                default:
                    $users = $userRepository->findAll();
            }
        } else {
            $users = $userRepository->findAll();
        }
        
        // Convert to array for JSON
        $usersArray = [];
        foreach ($users as $user) {
            $usersArray[] = [
                'userId' => $user->getUserId(),
                'userUsername' => $user->getUserUsername(),
                'userEmail' => $user->getUserEmail(),
                'userPassword' => $user->getUserPassword(),
                'userFirstname' => $user->getUserFirstname(),
                'userLastname' => $user->getUserLastname(),
                'userBirthday' => $user->getUserBirthday() ? $user->getUserBirthday()->format('Y-m-d') : null,
                'userGender' => $user->getUserGender(),
                'userPicture' => $user->getUserPicture(),
                'userPhonenumber' => $user->getUserPhonenumber(),
                'userLevel' => $user->getUserLevel(),
                'userRole' => $user->getUserRole()
            ];
        }
        
        return $this->json($usersArray);
    }

    #[Route('/search-criteria', name: 'app_user_search_criteria', methods: ['GET'])]
    public function searchByCriteria(Request $request, UserRepository $userRepository): Response
    {
        // Get search parameters from query string
        $criteria = $request->query->get('criteria');
        $value = $request->query->get('value');
        
        // Search users based on selected criteria
        $users = [];
        
        if ($criteria && $value) {
            switch ($criteria) {
                case 'username':
                    $users = $userRepository->findBySearchCriteria($value, null, null);
                    break;
                case 'firstname':
                    $users = $userRepository->findBySearchCriteria(null, $value, null);
                    break;
                case 'lastname':
                    $users = $userRepository->findBySearchCriteria(null, null, $value);
                    break;
                default:
                    $users = $userRepository->findAll();
            }
        } else {
            // Si aucun critère ou valeur, retourner tous les utilisateurs
            $users = $userRepository->findAll();
        }
        
        // Rendre le template partiel
        return $this->render('user/_users_table_body.html.twig', [
            'users' => $users,
        ]);
    }

   // En haut du fichier


// ... dans la classe UserController (qui a #[Route('/user')] au-dessus !)
#[Route('/ajax-search', name: 'ajax_user_search', methods: ['GET'])]
public function ajaxSearch(Request $request, UserRepository $userRepository): JsonResponse
{
    $criteria = $request->query->get('criteria');
    $value = $request->query->get('value');

    $users = [];
    if ($criteria && $value) {
        switch ($criteria) {
            case 'username':
                $users = $userRepository->findBySearchCriteria($value, null, null);
                break;
            case 'firstname':
                $users = $userRepository->findBySearchCriteria(null, $value, null);
                break;
            case 'lastname':
                $users = $userRepository->findBySearchCriteria(null, null, $value);
                break;
        }
    } else {
        $users = $userRepository->findAll();
    }

    $usersArray = [];
    foreach ($users as $user) {
        $usersArray[] = [
            'userId' => $user->getUserId(),
            'userUsername' => $user->getUserUsername(),
            'userEmail' => $user->getUserEmail(),
            'userPassword' => $user->getUserPassword(),
            'userFirstname' => $user->getUserFirstname(),
            'userLastname' => $user->getUserLastname(),
            'userBirthday' => $user->getUserBirthday() ? $user->getUserBirthday()->format('Y-m-d') : null,
            'userGender' => $user->getUserGender(),
            'userPicture' => $user->getUserPicture(),
            'userPhonenumber' => $user->getUserPhonenumber(),
            'userLevel' => $user->getUserLevel(),
            'userRole' => $user->getUserRole()
        ];
    }

    return $this->json($usersArray);
}

    #[Route('/search-simple', name: 'app_user_search_simple', methods: ['GET'])]
    public function searchSimple(Request $request): Response
    {
        $criteria = $request->query->get('criteria', '');
        $value = $request->query->get('value', '');
        
        return $this->json([
            'success' => true,
            'received' => [
                'criteria' => $criteria,
                'value' => $value
            ],
            'message' => 'Search route works!'
        ]);
    }

    #[Route('/test', name: 'app_user_test', methods: ['GET'])]
    public function test(): Response
    {
        return $this->json(['status' => 'ok', 'message' => 'Test route works!']);
    }

    #[Route('/api-ajax-search', name: 'app_user_api_ajax_search', methods: ['GET'])]
    public function apiAjaxSearch(Request $request, UserRepository $userRepository): JsonResponse
    {
        $criteria = $request->query->get('criteria');
        $value = $request->query->get('value');

        $users = [];
        if ($criteria && $value) {
            switch ($criteria) {
                case 'username':
                    $users = $userRepository->findBySearchCriteria($value, null, null);
                    break;
                case 'firstname':
                    $users = $userRepository->findBySearchCriteria(null, $value, null);
                    break;
                case 'lastname':
                    $users = $userRepository->findBySearchCriteria(null, null, $value);
                    break;
            }
        } else {
            $users = $userRepository->findAll();
        }

        $usersArray = [];
        foreach ($users as $user) {
            $usersArray[] = [
                'userId' => $user->getUserId(),
                'userUsername' => $user->getUserUsername(),
                'userEmail' => $user->getUserEmail(),
                'userPassword' => $user->getUserPassword(),
                'userFirstname' => $user->getUserFirstname(),
                'userLastname' => $user->getUserLastname(),
                'userBirthday' => $user->getUserBirthday() ? $user->getUserBirthday()->format('Y-m-d') : null,
                'userGender' => $user->getUserGender(),
                'userPicture' => $user->getUserPicture(),
                'userPhonenumber' => $user->getUserPhonenumber(),
                'userLevel' => $user->getUserLevel(),
                'userRole' => $user->getUserRole()
            ];
        }

        return $this->json($usersArray);
    }

    #[Route('/{user_id}/toggle-active', name: 'app_user_toggle_active', methods: ['POST'])]
    public function toggleActive(
        Request $request,
        User $user,
        EntityManagerInterface $entityManager
    ): Response {
        if ($this->isCsrfTokenValid('toggle-active'.$user->getUserId(), $request->request->get('_token'))) {
            $user->setIsActive(!$user->getIsActive());
            $entityManager->flush();
            
            $this->addFlash(
                'success',
                sprintf('User %s has been %s', 
                    $user->getUserUsername(),
                    $user->getIsActive() ? 'activated' : 'deactivated'
                )
            );
        }

        return $this->redirectToRoute('app_user_index');
    }

    #[Route('/api/levels', name: 'app_user_api_levels', methods: ['GET'])]
    public function getUserLevels(UserRepository $userRepository): JsonResponse
    {
        // Get user levels and count for each level
        $usersByLevel = $userRepository->createQueryBuilder('u')
            ->select('u.user_level as level, COUNT(u.user_id) as userCount')
            ->where('u.user_level IS NOT NULL')
            ->groupBy('u.user_level')
            ->getQuery()
            ->getResult();
        
        return $this->json($usersByLevel);
    }
}
