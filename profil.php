<?php
// Démarrer la session
session_start();

// Si l'utilisateur n'est pas connecté, rediriger vers la connexion
if (!isset($_SESSION['id_etudiant'])) {
    header('Location: connexion.php');
    exit;
}

// Connexion à la base pour afficher les infos de l'étudiant
try {
    $pdo = new PDO("mysql:host=localhost;dbname=bdd_stage;charset=utf8mb4", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("SELECT `N°Etudiant`, Nom, Prenom, Email, Formation FROM etudiant WHERE `N°Etudiant` = ?");
    $stmt->execute([$_SESSION['id_etudiant']]);
    $etudiant = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données.");
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon profil - MMI Meaux</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-50 font-sans min-h-screen flex flex-col justify-between">

    <nav class="bg-blue-900 text-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <span class="text-xl font-bold tracking-wide">MMI Meaux</span>
                <span class="bg-blue-700 text-xs px-2 py-1 rounded-sm uppercase font-semibold text-blue-200">Stages</span>
            </div>
            <div class="space-x-4 text-sm font-medium">
                <a href="accueil.html" class="hover:text-blue-200 transition">Accueil</a>
                <a href="logout.php" class="bg-red-600 px-4 py-2 rounded-md hover:bg-red-700 transition">Déconnexion</a>
            </div>
        </div>
    </nav>

    <main class="flex-grow flex items-center justify-center px-4 py-12">
        <div class="bg-white p-8 rounded-lg shadow-md max-w-md w-full border border-gray-100">
            
            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold text-gray-800">Mon espace étudiant</h1>
                <p class="text-sm text-gray-500 mt-1">Bienvenue sur votre profil</p>
            </div>

            <div class="space-y-4 text-sm">
                <div class="border-b border-gray-100 pb-3">
                    <p class="text-gray-500">N° étudiant</p>
                    <p class="font-medium text-gray-800"><?php echo htmlspecialchars($etudiant['N°Etudiant'] ?? ''); ?></p>
                </div>
                <div class="border-b border-gray-100 pb-3">
                    <p class="text-gray-500">Email</p>
                    <p class="font-medium text-gray-800"><?php echo htmlspecialchars($etudiant['Email'] ?? $_SESSION['email']); ?></p>
                </div>
                <div class="border-b border-gray-100 pb-3">
                    <p class="text-gray-500">Nom</p>
                    <p class="font-medium text-gray-800">
                        <?php echo !empty($etudiant['Nom']) ? htmlspecialchars($etudiant['Nom']) : 'Non renseigné'; ?>
                    </p>
                </div>
                <div class="border-b border-gray-100 pb-3">
                    <p class="text-gray-500">Prénom</p>
                    <p class="font-medium text-gray-800">
                        <?php echo !empty($etudiant['Prenom']) ? htmlspecialchars($etudiant['Prenom']) : 'Non renseigné'; ?>
                    </p>
                </div>
                <div>
                    <p class="text-gray-500">Formation</p>
                    <p class="font-medium text-gray-800">
                        <?php echo !empty($etudiant['Formation']) ? htmlspecialchars($etudiant['Formation']) : 'Non renseigné'; ?>
                    </p>
                </div>
            </div>

            <div class="mt-8 text-center">
                <a href="logout.php" class="inline-block w-full text-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-4 rounded-md text-sm transition">
                    Se déconnecter
                </a>
            </div>

        </div>
    </main>

    <footer class="bg-gray-900 text-gray-300 border-t border-gray-800 font-sans">
        <div class="bg-gray-950 text-gray-500 text-xs py-4 border-t border-gray-800">
            <div class="max-w-7xl mx-auto px-4 text-center">
                &copy; 2026 BUT MMI Meaux — Projet SAé Suivi des Stages. Tous droits réservés.
            </div>
        </div>
    </footer>

</body>
</html>
