<?php

namespace app\Controllers;

use \Core\Controller;
use \Core\View;

class Chat extends Controller
{
    public function index()
    {
        if (isset($_SESSION['name'])) {
            $name = $_SESSION['name'] ?? '';
            $contents = $this->getContents('log.txt');
            View::renderTemplate('Chat/index.html', ['name' => $name, 'contents' => $contents]);
        } else {
            $this->redirect('/chat/login');
        }
    }

    public function getContents($file = 'log.txt')
    {
        if(file_exists($file) && filesize($file) > 0){
            $handle = fopen($file, "r");
            $contents = [];
            while (($buffer = fgets($handle)) !== false) {
                $arr = explode(' ', $buffer);
                $contents[] = $arr;
            }
            fclose($handle);
            return $contents;
        }
    }

    public function getJsonContents($file = 'log.txt')
    {
        if(file_exists($file) && filesize($file) > 0){
            $handle = fopen($file, "r");
            $contents = [];
            while (($buffer = fgets($handle)) !== false) {
                $arr = explode(' ', $buffer);
                $contents[] = $arr;
            }
            fclose($handle);
            echo json_encode($contents);
        }
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if($_POST['name'] != ""){
                $_SESSION['name'] = stripslashes(htmlspecialchars($_POST['name']));
                $this->redirect('/chat/index');
            }
            else{
                echo '<span class="error">Please type in a name</span>';
            }
        } else {
            View::renderTemplate('Chat/loginForm.html');
        }
    }

    public function logout()
    {
        // write the user logout message to log.txt
        $fp = fopen("/log.txt", 'a');
        fwrite($fp, "<div class='msgln'><i>User ". $_SESSION['name'] ." has left the chat session.</i><br></div>");
        fclose($fp);
        // clear the session
        // Unset all of the session variables.
        $_SESSION = array();

        // If it's desired to kill the session, also delete the session cookie.
        // Note: This will destroy the session, and not just the session data!
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        // Finally, destroy the session.
        session_destroy();

        $this->redirect('/chat/index');
    }

    public function post()
    {
        if(isset($_SESSION['name'])){
            $text = $_POST['text'];
            // log.txt前面加上/报错
            $fp = fopen("log.txt", 'a');
            fwrite($fp, date("g:i A") . ' ' . $_SESSION['name'] . ' ' . stripslashes(htmlspecialchars($text)) . "\n");
            fclose($fp);
        }
    }
}