<?php
session_start();
if (!isset($_SESSION['username']) || !$_SESSION['username']) {
    header("Location: login.php"); // Redirect if not a user
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Chat Room</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        #chat-container {
            width: 480px; /* Fixed width */
            height: 600px; /* Fixed height */
            background-color: white;
            border-radius: 5px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        #chat-header {
            background-color: #007bff;
            color: #fff;
            padding: 15px;
            text-align: center;
            font-weight: bold;
            font-size: 1.4rem;
        }
        #faq-section {
            text-align: center;
            margin-bottom: 20px;
        }

        #chat-container .faq-title {
            font-size: 10px;
            font-weight: bold;
            color: #333;
        }

        #faq-buttons-container {
            display: flex;
            overflow-x: auto;
            max-width: 100%;
            gap: 8px;
            padding: 8px;
            background-color: #f9f9f9;
            scrollbar-width: thin;
            scrollbar-color: #007bff #f1f3f5;
            justify-content: flex-start;
            border-radius: 8px; /* Added rounded corners for the container */
        }

        #faq-buttons-container::-webkit-scrollbar {
            height: 6px;
        }

        #faq-buttons-container::-webkit-scrollbar-thumb {
            background-color: #007bff;
            border-radius: 4px;
        }

        #chat-container .faq-button {
            flex-shrink: 0;
            min-width: calc(33.33% - 7px); /* Show 3 buttons at a time */
            max-width: calc(33.33% - 7px);
            padding: 8px 15px; /* Reduced padding */
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            font-size: 0.75rem; /* Reduced font size */
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        #chat-container .faq-button:hover {
            background-color: #0056b3;
            transform: translateY(-1px);
            box-shadow: 0px 3px 8px rgba(0, 0, 0, 0.2);
        }


        #chat-box {
            flex: 1;
            padding: 15px;
            overflow-y: auto;
            background-color: #f9f9f9;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        #chat-container .message {
            padding: 10px 15px;
            border-radius: 20px;
            max-width: 70%;
            font-size: 0.8rem;
            line-height: 1.4;
            display: inline-block;
            word-wrap: break-word;
        }
        #chat-container .message.user {
            background-color: #e6e6e6;
            align-self: flex-end;
            border-bottom-right-radius: 0;
        }
        #chat-container .message.admin {
            background-color: #dcf8c6;
            align-self: flex-start;
            border-bottom-left-radius: 0;
        }
        #chat-container .timestamp {
            font-size: 0.75rem;
            color: #999;
            margin-top: 5px;
            text-align: right;
        }
        #chat-input-container {
            display: flex;
            padding: 10px;
            background-color: #f1f3f5;
            border-top: 1px solid #ddd;
        }
        #chat-input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 20px;
            outline: none;
            font-size: 0.9rem;
            margin-right: 10px;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
        }
        #chat-input:focus {
            border-color: #007bff;
        }
        #chat-container button {
            width: 50px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
        }
        #chat-container button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<div id="chat-container">
    <div id="chat-header">Chat Support</div>
    <div id="faq-section">
    <h3 class="faq-title" style="margin-top: 10px;">Frequently Asked Questions</h3>
        <div id="faq-buttons-container">
            <button class="faq-button" onclick="sendFAQ('What are your business hours?')">Business Hours</button>
            <button class="faq-button" onclick="sendFAQ('How can I contact support?')">Contact Support</button>
            <button class="faq-button" onclick="sendFAQ('What is your return policy?')">Return Policy</button>
            <button class="faq-button" onclick="sendFAQ('Where can I find the tracking information?')">Tracking Info</button>
            <button class="faq-button" onclick="sendFAQ('Do you offer international shipping?')">International Shipping</button>
        </div>
    </div>

    <div id="chat-box"></div>
    <div id="chat-input-container">
        <textarea id="chat-input" placeholder="Type your message..." rows="1"></textarea>
        <button onclick="sendMessage()"><i class="fas fa-paper-plane"></i></button>
    </div>
</div>

<script>
    const other_id = 1; // This should be set to the admin's user ID

    function fetchMessages() {
        fetch("fetch_message.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: "receiver_id=" + encodeURIComponent(other_id)
        })
        .then(response => response.text())
        .then(data => {
            document.getElementById("chat-box").innerHTML = data;
            document.getElementById("chat-box").scrollTop = document.getElementById("chat-box").scrollHeight;
        })
        .catch(error => console.error("Error fetching messages:", error));
    }

    function sendMessage() {
    const message = document.getElementById("chat-input").value;
    if (message.trim() === "") return;

    displayMessage(message, true); // Display user message immediately
    document.getElementById("chat-input").value = "";

    // Send the user's message
    fetch("send_message.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `message=${encodeURIComponent(message)}&receiver_id=${other_id}&sender_type=user`
    });
    }

    function sendFAQ(faqQuestion) {
    // Display the user's question immediately in the chat
    displayMessage(faqQuestion, true);

    // Send the question to the server to appear in the history as a user message
    fetch("send_message.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `message=${encodeURIComponent(faqQuestion)}&receiver_id=${other_id}&sender_type=user`
    })
    .then(() => {
        // Get the automated response
        const faqResponse = faqResponses[faqQuestion];

        // Send the automated response to the server as an admin message
        return fetch("send_message.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `message=${encodeURIComponent(faqResponse)}&receiver_id=${other_id}&sender_type=admin`
        }).then(() => {
            displayMessage(faqResponse, false); // Display admin reply
        });
    })
    .catch(error => console.error("Error sending FAQ message:", error));
    }


    // Modified displayMessage function to apply 'admin' class for automated responses
    function displayMessage(message, isUser) {
    const chatBox = document.getElementById("chat-box");
    const messageDiv = document.createElement("div");
    messageDiv.className = `message ${isUser ? 'user' : 'admin'}`; // Admin class for FAQ replies
    messageDiv.innerHTML = `<span>${message}</span><span class='timestamp'>Just now</span>`;
    chatBox.appendChild(messageDiv);
    chatBox.scrollTop = chatBox.scrollHeight; // Auto-scroll to the bottom
    }
    setInterval(fetchMessages, 3000);
    document.addEventListener("DOMContentLoaded", fetchMessages);

</script>
</body>
</html>
