<?php
// Démarrer la session (nécessaire pour stocker l'utilisateur connecté)
session_start();

// Messages affichés sur la page
$erreur = '';
$succes = '';

// Message après une inscription réussie (redirection depuis l'inscription)
if (isset($_GET['succes']) && $_GET['succes'] == '1') {
    $succes = 'Inscription réussie ! Vous pouvez maintenant vous connecter.';
}

// Connexion à la base de données avec PDO
try {
    $pdo = new PDO("mysql:host=localhost;dbname=bdd_stage;charset=utf8mb4", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données.");
}

// Traitement des formulaires envoyés en POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Récupérer l'action : "inscription" ou "connexion" (par défaut connexion)
    $action = $_POST['action'] ?? 'connexion';

    // ---------- INSCRIPTION ----------
    if ($action === 'inscription') {

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $password_confirm = $_POST['password_confirm'] ?? '';

        // Vérifier que les champs ne sont pas vides
        if ($email === '' || $password === '' || $password_confirm === '') {
            $erreur = 'Veuillez remplir tous les champs.';
        }
        // Vérifier que les deux mots de passe sont identiques
        elseif ($password !== $password_confirm) {
            $erreur = 'Les mots de passe ne correspondent pas.';
        }
        // Vérifier que l'email est valide
        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erreur = 'Adresse e-mail invalide.';
        }
        else {
            // Vérifier si l'email existe déjà
            $stmt = $pdo->prepare("SELECT `N°Etudiant` FROM etudiant WHERE Email = ?");
            $stmt->execute([$email]);

            if ($stmt->fetch()) {
                $erreur = 'Cette adresse e-mail est déjà utilisée.';
            } else {
                // Hasher le mot de passe (ne jamais stocker en clair)
                $mot_de_passe_hash = password_hash($password, PASSWORD_DEFAULT);

                // Insérer le nouvel étudiant (Nom, Prénom, Formation = NULL)
                $stmt = $pdo->prepare(
                    "INSERT INTO etudiant (Nom, Prenom, Email, Formation, mot_de_passe) VALUES (NULL, NULL, ?, NULL, ?)"
                );
                $stmt->execute([$email, $mot_de_passe_hash]);

                // Rediriger vers la page de connexion avec un message de succès
                header('Location: connexion.php?succes=1');
                exit;
            }
        }

    // ---------- CONNEXION ----------
    } else {

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($email === '' || $password === '') {
            $erreur = 'Veuillez remplir tous les champs.';
        } else {
            // Chercher l'étudiant par email (requête préparée = protection injection SQL)
            $stmt = $pdo->prepare("SELECT `N°Etudiant`, Email, mot_de_passe FROM etudiant WHERE Email = ?");
            $stmt->execute([$email]);
            $etudiant = $stmt->fetch(PDO::FETCH_ASSOC);

            // Vérifier l'email et le mot de passe
            if ($etudiant && password_verify($password, $etudiant['mot_de_passe'])) {
                // Connexion réussie : enregistrer les infos en session
                $_SESSION['id_etudiant'] = $etudiant['N°Etudiant'];
                $_SESSION['email'] = $etudiant['Email'];

                header('Location: profil.php');
                exit;
            } else {
                $erreur = 'Email ou mot de passe incorrect.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Stages - MMI Meaux</title>
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
                <a href="accueil.html" class="">Accueil</a>
                <a href="#" class="">Aide (ESUP)</a>
            </div>
        </div>
    </nav>

    <main class=" flex items-center justify-center px-4 py-12">
        <div class="bg-white p-8 rounded-lg shadow-md max-w-md w-full border border-gray-100">
            
            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold text-gray-800">Portail de Suivi des Stages</h1>
                <p class="text-sm text-gray-500 mt-1">Connectez-vous pour accéder à votre espace personnalisé</p>
            </div>

            <?php if ($succes !== ''): ?>
            <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-800 rounded-md text-sm">
                <?php echo htmlspecialchars($succes); ?>
            </div>
            <?php endif; ?>

            <?php if ($erreur !== ''): ?>
            <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-800 rounded-md text-sm">
                <?php echo htmlspecialchars($erreur); ?>
            </div>
            <?php endif; ?>

            <form action="connexion.php" method="POST" class="space-y-5">
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Adresse e-mail universitaire</label>
                    <input type="email" id="email" name="email" required 
                        placeholder="etudiant@univ-eiffel.fr"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                </div>

                <div>
                    <div class="flex justify-between items-center mb-1">
                        <label for="password" class="text-sm font-medium text-gray-700">Mot de passe</label>
                        <a href="#" class="text-xs text-blue-600 hover:underline">Mot de passe oublié ?</a>
                    </div>
                    <input type="password" id="password" name="password" required 
                        placeholder="••••••••"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
                </div>

                <div>
                    <button type="submit" 
                        class="w-full bg-blue-650 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md shadow transition duration-150 ease-in-out text-sm cursor-pointer">
                        Se connecter
                    </button>
                </div>
            </form>

            <div class="relative flex py-5 items-center">
                <div class="flex-grow border-t border-gray-200"></div>
                <span class="flex-shrink mx-4 text-gray-400 text-xs uppercase">Nouveau stagiaire ?</span>
                <div class="flex-grow border-t border-gray-200"></div>
            </div>

            <div class="text-center">
                <a href="inscription.html" class="inline-block w-full text-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-4 rounded-md text-sm transition">
                    Créer un compte étudiant
                </a>
            </div>

        </div>
    </main>

<footer class="bg-gray-900 text-gray-300 border-t border-gray-800 font-sans">
        
        <div class="max-w-7xl mx-auto px-4 py-12 grid grid-cols-1 md:grid-cols-4 gap-8">
            
            <div class="space-y-4">
                <div class="flex items-center space-x-2 text-white">
                    <span class="text-lg font-bold tracking-wide">MMI Meaux</span>
                    <span class="bg-blue-600 text-[10px] px-1.5 py-0.5 rounded uppercase font-bold text-blue-100">Stages</span>
                </div>
                <p class="text-sm text-gray-400 leading-relaxed">
                    Application officielle de suivi et de gestion des stages du département MMI de Meaux (Université Gustave Eiffel).
                </p>
            </div>

            <div>
                <h3 class="text-white text-sm font-semibold uppercase tracking-wider mb-4">Navigation</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="#" class="hover:text-blue-400 transition">Accueil</a></li>
                    <li><a href="#" class="hover:text-blue-400 transition">Offres de stages</a></li>
                    <li><a href="#" class="hover:text-blue-400 transition">Espace Étudiant</a></li>
                    <li><a href="#" class="hover:text-blue-400 transition">Espace Entreprise</a></li>
                </ul>
            </div>

            <div>
                <h3 class="text-white text-sm font-semibold uppercase tracking-wider mb-4">Ressources</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="#" class="hover:text-blue-400 transition">Portail ESUP</a></li> <li><a href="https://www.univ-gustave-eiffel.fr" target="_blank" class="hover:text-blue-400 transition">Univ Gustave Eiffel</a></li>
                    <li><a href="#" class="hover:text-blue-400 transition">Guide des stages</a></li>
                    <li><a href="#" class="hover:text-blue-400 transition">Contact Secrétariat</a></li>
                </ul>
            </div>

            <div>
                <h3 class="text-white text-sm font-semibold uppercase tracking-wider mb-4">Suivez MMI Meaux</h3>
                <div class="flex space-x-4 mb-4">
                    <a href="#" class="text-gray-400 hover:text-blue-500 transition" aria-label="LinkedIn">
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                    </a>
                </div>
                <div class="text-xs text-gray-500">
                    <p>Environnement de dev :</p>
                    <p class="font-mono mt-1">PHP / MySQL (Mutualisé)</p>
                </div>
            </div>

        </div>

        <div class="bg-gray-950 text-gray-500 text-xs py-4 border-t border-gray-800">
            <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row justify-between items-center space-y-2 sm:space-y-0">
                <div>
                    &copy; 2026 BUT MMI Meaux — Projet SAé Suivi des Stages. Tous droits réservés.
                </div>
                <div class="flex space-x-4">
                    <a href="#" class="">Mentions légales</a>
                    <span>•</span>
                    <a href="#" class="">Politique de confidentialité</a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
