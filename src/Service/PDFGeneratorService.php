<?php

namespace App\Service;

use FPDF;

class PDFGeneratorService
{
    public function generateFacturisationPDF(string $filePath, string $methodePaiement, float $amount, string $datePaiement, string $statut, string $clientEmail): void
    {
        // Créer une instance de FPDF
        $pdf = new FPDF();
        $pdf->AddPage();
        
        // Définir le titre en rouge gras
        $pdf->SetTextColor(255, 0, 0); // Rouge
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'Facture', 0, 1, 'C');
        
        // Passer à la ligne suivante
        $pdf->Ln(10);
        
        // Afficher l'e-mail du client
        $pdf->SetFont('Arial', '', 12);
        $pdf->SetTextColor(0, 0, 0); // Noir
        $pdf->Cell(0, 10, 'Client: ' . $clientEmail, 0, 1); // Ajouter l'email du client
        
        // Passer à la ligne suivante
        $pdf->Ln(5);
        
        // Changer la couleur du texte en vert pour les titres
        $pdf->SetTextColor(0, 128, 0); // Vert

        // Créer le tableau avec les titres en vert
        $pdf->Cell(70, 10, 'Methode de Paiement', 1);
        $pdf->Cell(70, 10, 'Montant', 1);
        $pdf->Cell(70, 10, 'Date de Paiement', 1);
        $pdf->Cell(70, 10, 'Statut', 1);
        $pdf->Ln();

        // Revenir à la couleur noire pour les données
        $pdf->SetTextColor(0, 0, 0); // Noir
        
        // Ajouter les détails dans le tableau
        $pdf->Cell(70, 10, $methodePaiement, 1);
        $pdf->Cell(70, 10, number_format($amount, 2, ',', ' ') . ' euros', 1);
        $pdf->Cell(70, 10, $datePaiement, 1);
        $pdf->Cell(70, 10, $statut, 1);
        $pdf->Ln(10);

        // Message de remerciement
        $pdf->SetFont('Arial', 'I', 12);
        $pdf->Cell(0, 10, "Merci d'avoir choisi NaviFly", 0, 1, 'C');
        
        // Sauvegarder le PDF dans le fichier
        $pdf->Output('F', $filePath);
    }
}