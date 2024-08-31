<?php
        $hostname = "localhost";
        $dbname = "foodshop";
        $username = "root";
        $password = "";

        try {
            $conn = new PDO("mysql:hostname=$hostname;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            $response['success'] = false;
            $response['messages'][] = "Connection failed: " . $e->getMessage();
            echo json_encode($response);
            exit;
        }