<?php

require 'db.php';

$db = getDB();
if ($db) {
    echo "Connected to the database successfully.";
} else {
    echo "Failed to connect to the database.";
}

$stmt = $db->query("SELECT * FROM agents LIMIT 1");
$agents = $stmt->fetchAll(PDO::FETCH_ASSOC);
if ($agents) {
    echo "Connected and data retrieved successfully.";
} else {
    echo "Failed to retrieve data.";
}
// gestion de la connexion
function authenticate() {
    if (!isset($_SERVER['PHP_AUTH_USER']) || !isset($_SERVER['PHP_AUTH_PW'])) {
        header('HTTP/1.0 401 Unauthorized');
        echo 'Unauthorized';
        exit;
    } else {
        if ($_SERVER['PHP_AUTH_USER'] !== 'admin' || $_SERVER['PHP_AUTH_PW'] !== 'password123') {
            header('HTTP/1.0 401 Unauthorized');
            echo 'Unauthorized';
            exit;
        }
    }
    error_log("Authenticated successfully");
}

authenticate();

$requestMethod = $_SERVER['REQUEST_METHOD'];
$requestUri = $_SERVER['REQUEST_URI'];

switch (true) {
    case strpos($requestUri, '/agents') !== false:
        handleAgents($requestMethod);
        break;
    
    case strpos($requestUri, '/clients') !== false:
        handleClients($requestMethod);
        break;

    case strpos($requestUri, '/canaux') !== false:
        handleCanaux($requestMethod);
        break;

    case strpos($requestUri, '/conversations') !== false:
        handleConversations($requestMethod);
        break;

    case strpos($requestUri, '/messages') !== false:
        handleMessages($requestMethod);
        break;

    case strpos($requestUri, '/statistiques') !== false:
        handleStatistiques($requestMethod);
        break;

    default:
        header('HTTP/1.0 404 Not Found');
        echo 'Not Found';
        break;
        echo "Request URI: " . htmlspecialchars($requestUri);

}

// route des agents
function handleAgents($method) {
    $db = getDB();
    
    switch ($method) {
        case 'GET':
    echo json_encode(['message' => 'Agents endpoint is working']);
    break;
        case 'GET':
            $stmt = $db->query("SELECT * FROM agents");
            $agents = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($agents);
            break;

        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            $stmt = $db->prepare("INSERT INTO agents (nom, email, password) VALUES (:nom, :email, :password)");
            $stmt->execute([
                ':nom' => $data['nom'],
                ':email' => $data['email'],
                ':password' => password_hash($data['password'], PASSWORD_DEFAULT)
            ]);
            echo json_encode(['message' => 'Agent created']);
            break;

        case 'PUT':
            $data = json_decode(file_get_contents('php://input'), true);
            $stmt = $db->prepare("UPDATE agents SET nom = :nom, email = :email WHERE agent_id = :id");
            $stmt->execute([
                ':nom' => $data['nom'],
                ':email' => $data['email'],
                ':id' => $data['id']
            ]);
            echo json_encode(['message' => 'Agent updated']);
            break;

        case 'DELETE':
            $data = json_decode(file_get_contents('php://input'), true);
            $stmt = $db->prepare("DELETE FROM agents WHERE agent_id = :id");
            $stmt->execute([':id' => $data['id']]);
            echo json_encode(['message' => 'Agent deleted']);
            break;

        default:
            header('HTTP/1.0 405 Method Not Allowed');
            echo 'Method Not Allowed';
            break;
    }
}

// route des clients
function handleClients($method) {
    $db = getDB();
    
    switch ($method) {
        case 'GET':
            $stmt = $db->query("SELECT * FROM clients");
            $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($clients);
            break;

        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            $stmt = $db->prepare("INSERT INTO clients (nom, telephone) VALUES (:nom, :telephone)");
            $stmt->execute([
                ':nom' => $data['nom'],
                ':telephone' => $data['telephone']
            ]);
            echo json_encode(['message' => 'Client created']);
            break;

        case 'PUT':
            $data = json_decode(file_get_contents('php://input'), true);
            $stmt = $db->prepare("UPDATE clients SET nom = :nom, telephone = :telephone WHERE client_id = :id");
            $stmt->execute([
                ':nom' => $data['nom'],
                ':telephone' => $data['telephone'],
                ':id' => $data['id']
            ]);
            echo json_encode(['message' => 'Client updated']);
            break;

        case 'DELETE':
            $data = json_decode(file_get_contents('php://input'), true);
            $stmt = $db->prepare("DELETE FROM clients WHERE client_id = :id");
            $stmt->execute([':id' => $data['id']]);
            echo json_encode(['message' => 'Client deleted']);
            break;

        default:
            header('HTTP/1.0 405 Method Not Allowed');
            echo 'Method Not Allowed';
            break;
    }
}

// route des canaux
function handleCanaux($method) {
    $db = getDB();
    
    switch ($method) {
        case 'GET':
            $stmt = $db->query("SELECT * FROM canaux");
            $canaux = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($canaux);
            break;

        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            $stmt = $db->prepare("INSERT INTO canaux (nom) VALUES (:nom)");
            $stmt->execute([':nom' => $data['nom']]);
            echo json_encode(['message' => 'Canal created']);
            break;

        case 'DELETE':
            $data = json_decode(file_get_contents('php://input'), true);
            $stmt = $db->prepare("DELETE FROM canaux WHERE canal_id = :id");
            $stmt->execute([':id' => $data['id']]);
            echo json_encode(['message' => 'Canal deleted']);
            break;

        default:
            header('HTTP/1.0 405 Method Not Allowed');
            echo 'Method Not Allowed';
            break;
    }
}

// Gestion des conversations
function handleConversations($method) {
    $db = getDB();
    
    switch ($method) {
        case 'GET':
            $stmt = $db->query("SELECT * FROM conversations");
            $conversations = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($conversations);
            break;

        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            $stmt = $db->prepare("INSERT INTO conversations (client_id, agent_id, canal_id, debut) VALUES (:client_id, :agent_id, :canal_id, NOW())");
            $stmt->execute([
                ':client_id' => $data['client_id'],
                ':agent_id' => $data['agent_id'],
                ':canal_id' => $data['canal_id']
            ]);
            echo json_encode(['message' => 'Conversation created']);
            break;

        case 'PUT':
            $data = json_decode(file_get_contents('php://input'), true);
            $stmt = $db->prepare("UPDATE conversations SET fin = NOW() WHERE conversation_id = :id");
            $stmt->execute([':id' => $data['id']]);
            echo json_encode(['message' => 'Conversation closed']);
            break;

        case 'DELETE':
            $data = json_decode(file_get_contents('php://input'), true);
            $stmt = $db->prepare("DELETE FROM conversations WHERE conversation_id = :id");
            $stmt->execute([':id' => $data['id']]);
            echo json_encode(['message' => 'Conversation deleted']);
            break;

        default:
            header('HTTP/1.0 405 Method Not Allowed');
            echo 'Method Not Allowed';
            break;
    }
}

// route des messages
function handleMessages($method) {
    $db = getDB();
    
    switch ($method) {
        case 'GET':
            $stmt = $db->query("SELECT * FROM messages");
            $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($messages);
            break;

        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            $stmt = $db->prepare("INSERT INTO messages (conversation_id, contenu, date_envoi, canal_id, expediteur) VALUES (:conversation_id, :contenu, NOW(), :canal_id, :expediteur)");
            $stmt->execute([
                ':conversation_id' => $data['conversation_id'],
                ':contenu' => $data['contenu'],
                ':canal_id' => $data['canal_id'],
                ':expediteur' => $data['expediteur']
            ]);
            echo json_encode(['message' => 'Message sent']);
            break;

        case 'DELETE':
            $data = json_decode(file_get_contents('php://input'), true);
            $stmt = $db->prepare("DELETE FROM messages WHERE id = :id");
            $stmt->execute([':id' => $data['id']]);
            echo json_encode(['message' => 'Message deleted']);
            break;

        default:
            header('HTTP/1.0 405 Method Not Allowed');
            echo 'Method Not Allowed';
            break;
    }
}

// routre es kpi
function handleStatistiques($method) {
    $db = getDB();
    
    switch ($method) {
        case 'GET':
            $stmt = $db->query("SELECT * FROM statistiques");
            $statistiques = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($statistiques);
            break;

        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            $stmt = $db->prepare("INSERT INTO statistiques (date, total_echanges, total_echanges_par_canal, temps_attente_moyen, temps_traitement_moyen, duree_conversation_moyenne) VALUES (:date, :total_echanges, :total_echanges_par_canal, :temps_attente_moyen, :temps_traitement_moyen, :duree_conversation_moyenne)");
            $stmt->execute([
                ':date' => $data['date'],
                ':total_echanges' => $data['total_echanges'],
                ':total_echanges_par_canal' => json_encode($data['total_echanges_par_canal']),
                ':temps_attente_moyen' => $data['temps_attente_moyen'],
                ':temps_traitement_moyen' => $data['temps_traitement_moyen'],
                ':duree_conversation_moyenne' => $data['duree_conversation_moyenne']
            ]);
            echo json_encode(['message' => 'Statistique created']);
            break;

        default:
            header('HTTP/1.0 405 Method Not Allowed');
            echo 'Method Not Allowed';
            break;
    }
}
?>
