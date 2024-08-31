<?php
$title = "Mail";
include("../includes/sessions.php");
include("../includes/head.php");

if (!isloggedin() || $_SESSION['accounttype'] !== "director") {
    header("Location:../auth/index.php");
    die();
}

if (isset($_SESSION['uploadsuccess'])) {
    echo "" . $_SESSION['uploadsuccess'];
}
unset($_SESSION['uploadsuccess']);
?>
<style>
    .custom-alert {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1050;
        display: none;
    }

    .card-body {
        min-height: 500px; /* Increased height for the card body */
    }
</style>

<body id="page-top">
    <div id="wrapper">
        <?php
        include("../includes/auth.sidebar.php");
        include("../includes/auth.header.php");
        ?>

        <div class="container-fluid">
            <h3 class="text-dark mb-4">Send Workers Mail</h3>
            <div class="row justify-content-center min-vh-100 align-items-center mt-n7">
                <div class="col-lg-8"> <!-- Adjusted column width for better form layout -->
                    <div class="card shadow mt-n7">
                        <div class="card-header py-3">
                            <p class="text-primary m-0 font-weight-bold">Send Workers Mail</p>
                        </div>
                        <div class="card-body">
                            <form action="" method="POST" id="mailform">
                                <div class="form-group">
                                    <label for="sender">From:</label>
                                    <input type="text" name="sender" id="sender" class="form-control" value='<?php echo htmlspecialchars($_SESSION['userinfo']['email'])?>' required>
                                </div>
                                <div class="form-group">
                                    <label for="recipient">Send To:</label>
                                    <select name="recipient" id="recipient" class="form-control">
                                        <option value="">Select Group/Worker</option>
                                        <option value="all">All Workers</option>
                                        <option value="admin">All Admins</option>
                                        <option value="staff">All Staff</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="subject">Sender Name:</label>
                                    <input type="text" name="sendername" id="sendername" class="form-control" value='<?php echo htmlspecialchars($_SESSION['userinfo']['fname'])?>'>
                                </div>
                                <div class="form-group">
                                    <label for="subject">Subject:</label>
                                    <input type="text" name="subject" id="subject" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="message">Message:</label>
                                    <textarea name="message" id="message" class="form-control" rows="8" required></textarea> <!-- Increased rows to make it larger -->
                                </div>
                                <button type="submit" name="sendmail" class="btn btn-primary">Send Mail</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="success-alert" class="alert alert-success custom-alert" role="alert">
            <strong id="success-message"></strong>
        </div>
        <div id="danger-alert" class="alert alert-danger custom-alert" role="alert">
            <strong id="danger-message"></strong>
        </div>
    </div>

    <?php
    include("../includes/footer.php");
    include("../includes/script.php");
    ?>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const mailForm = document.getElementById("mailform");
            const workerSelect = document.getElementById("recipient");

            async function retrieveWorkers() {
                try {
                    const response = await fetch("../api/users/process.retrieveworkers.php");
                    if (!response.ok) {
                        throw new Error("Could not retrieve workers");
                    }

                    const data = await response.json();

                    if (data.success) {
                        showAlert('success', data.message);

                        // Clear existing options (if any) before adding new ones
                       // workerSelect.insertAdjacentHTML('afterend','<option value="">Select Name</option>');

                        // Loop through the worker data and create an option for each
                        data.result.forEach(worker => {
                            createOption(worker);
                        });

                    } else {
                        showAlert('danger', data.message);
                    }
                } catch (error) {
                    console.error(error);
                }
            }

            function createOption(worker) {
                const newOption = document.createElement("option");
                newOption.value = worker.email;
                newOption.textContent = `${worker.firstname} ${worker.lastname}`.toUpperCase();
                workerSelect.appendChild(newOption);
            }

            // Call the function to retrieve and populate worker data
            retrieveWorkers();

            // Send mail form submission
            async function SendMail(event) {
                event.preventDefault();

                const formdata = new FormData(mailForm);
                const formobj = Object.fromEntries(formdata.entries());

                try {
                    const response = await fetch("../api/mail/sendmail.php", {
                        method: "POST",
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(formobj)
                    });

                    if (!response.ok) {
                        throw new Error('Error sending data');
                    }

                    const data = await response.json();
                    if (data.success) {
                        showAlert('success', data.message);
                        mailForm.reset();
                    } else {
                        let errorMessages = [data.message];
                        errorMessages.forEach((msg) => {
                            showAlert("danger", msg);
                        });
                    }
                    
                } catch (error) {
                    console.error('Error:', error);
                }
            }

            mailForm.addEventListener('submit', SendMail);

            function showAlert(type, message) {
                let alertBox, messageBox, soundFile;
                if (type === 'success') {
                    alertBox = document.getElementById('success-alert');
                    messageBox = document.getElementById('success-message');
                    soundFile = '../sounds/mixkit-bell-notification-933.wav';
                } else if (type === 'danger') {
                    alertBox = document.getElementById('danger-alert');
                    messageBox = document.getElementById('danger-message');
                    soundFile = '../sounds/mixkit-cartoon-failure-piano-473.wav';
                }

                messageBox.textContent = message;
                alertBox.style.display = 'block';

                let audio = new Audio(soundFile);
                audio.play();

                setTimeout(() => {
                    alertBox.style.display = 'none';
                }, 3000);
            }
        });
    </script>
</body>
</html>
