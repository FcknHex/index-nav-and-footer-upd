<?php
include 'conn.php';
session_start();

// Check if a user is logged in
$isLoggedIn = isset($_SESSION['email']) && !empty($_SESSION['email']);
$profilePic = ''; // Placeholder for the profile picture
$isSeller = false; // Flag to check if the user is a seller

if ($isLoggedIn) {
    $email = $_SESSION['email'];

    // Query to get the profile picture from the database
    $query = "SELECT id, proflePicture, firstname, lastname FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        $profilePic = $user['proflePicture'];  // Assuming you store the path to the profile picture
        $userId = $user['id'];
        $firstname = $user['firstname'];
        $lastname = $user['lastname'];
    }

    // If no profile picture is available, use a default image
    // if (empty($profilePic)) {
    //     $profilePic = 'plant-bazaar.jpg';  // Path to a default profile picture
    // }

    // Query to check if the user is a seller
    $sellerQuery = "SELECT seller_id FROM sellers WHERE user_id = '$userId'";
    $sellerResult = mysqli_query($conn, $sellerQuery);

    if ($sellerResult && mysqli_num_rows($sellerResult) > 0) {
        $isSeller = true; // User is a seller
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="nav.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <script src="jquery.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4/dist/css/splide.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4/dist/js/splide.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <script src="node_modules/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
    
    <title>Plant-Bazaar</title>
</head>


    <div class="header">
        <nav class="navigation">
            <div class="logo">
                <span class="plant">PLANT</span>
                <p class="bazaar">-BAZAAR</p>
                <i class="fa-solid fa-spa"></i>
            </div>
            <div class="nav1">
                <a href="#" id="home">Home</a>
                <a href="#">Plants Categories</a>
                <a href="#" id="about">About</a>
                <a href="#">Contact Us</a>
                <?php if ($isLoggedIn): ?>
                <a href="#" id="chats">Chats</a>
                <?php endif;?>
            </div>
            <div class="login-signup">
                <?php if ($isLoggedIn): ?>
                    <!-- Show Profile Picture if user is logged in -->
                    <a href="#" class="profile-link">
                        <img src="ProfilePictures/<?php echo $profilePic; ?>" alt="Profile" class="profile-pic">
                    </a>
                    
                <?php else: ?>
                    <!-- Show Login button if user is not logged in -->
                    <a href="#" id="loginLink">Login</a>
                <?php endif; ?>
            </div>
        </nav>
        <div class="hamburger">
            <i class="fas fa-bars"></i>
        </div>
    </div>

    <div class="dropdown-menu">
        <?php if ($isLoggedIn): ?>
            <a href="#" class="profile-link">
                        <img src="ProfilePictures/<?php echo $profilePic; ?>" alt="Profile" class="profile-pic">
            </a>
            <a><p>Hello, <?php echo $firstname . ' ' . $lastname; ?></p> </a>
        <?php endif;?>
        <a href="#" id="home1">Home</a>
        <a href="#" id="about1">About</a>
        <a href="#">Contact</a>
        <?php if ($isLoggedIn): ?>
            <a href="#" id="logoutLink">Logout</a>
        <?php else:?>
        <a href="#" id="loginLink1">Login</a>
        <?php endif;?>
    </div>

    <div class="dropdown-profile">
   <?php
    if ($isLoggedIn) {
        echo'<p>Hello, ' . $firstname . ' ' . $lastname . '</p>';
    }?>
    <?php if ($isSeller): ?>
        <a href="Seller/seller_dashboard">Seller Dashboard</a> <!-- Change the link as needed for the seller's dashboard -->
    <?php else: ?>
        <a href="#">Be A Seller</a> <!-- Link to becoming a seller -->
    <?php endif; ?>
    <a href="editprofile.php">Edit Profile</a>
    <a href="#" id="logoutLink">Logout</a>
</div>

   
 

  <!-- Login Modal -->
<div id="loginModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Login</h2>
        <form method="POST" action="" id="loginForm">
            <input type="email" id="loginEmail" placeholder="Email" required>
            <div class="error-label" style="display: none;"></div>
            <input type="password" id="loginPassword" placeholder="Password" required>
            <div class="error-label" style="display: none;"></div>
            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="#" id="signupLink">Sign Up</a></p>
    </div>
</div>

<div class="about-us" id="aboutUs" style="display: none;">
        <?php include 'aboutUs.php'; ?>
</div>

<!-- Signup Modal -->
<div class="modal" id="modalOverlay"></div> <!-- Overlay for blur effect -->
<div id="signupModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Sign Up</h2>
        <form method="POST" action="" id="signupForm" enctype="multipart/form-data">
            <div class="form-group">
                <label for="signupEmail">Email</label>
                <input type="email" id="signupEmail" name="email" placeholder="Email" required>
            </div>

            <div class="form-group">
                <label for="signupPassword">Password</label>
                <input type="password" id="signupPassword" name="password" placeholder="Password" required>
            </div>

            <div class="form-group">
                <label for="signupFirstName">First Name</label>
                <input type="text" id="signupFirstName" name="firstname" placeholder="First Name" required>
            </div>

            <div class="form-group">
                <label for="signupLastName">Last Name</label>
                <input type="text" id="signupLastName" name="lastname" placeholder="Last Name" required>
            </div>

            <div class="form-group">
                <label for="signupGender">Gender</label>
                <select id="signupGender" name="gender" required>
                    <option value="" disabled selected>Select Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <div class="form-group">
                <label for="signupPhoneNumber">Phone Number</label>
                <input type="tel" id="signupPhoneNumber" name="phonenumber" placeholder="Phone Number" required>
            </div>

            <div class="form-group">
                <label for="signupAddress">Address</label>
                <input type="text" id="signupAddress" name="address" placeholder="Address" required>
            </div>

            <div class="form-group">
                <label for="signupProfilePicture">Profile Picture</label>
                <input type="file" id="signupProfilePicture" name="profilePicture" accept="image/*">
            </div>

            <!-- Submit Button -->
            <button type="submit">Sign Up</button>
        </form>
        <p>Already have an account? <a href="#" id="loginLink">Login</a></p>
    </div>
</div>

<!-- Modal HTML structure -->
<div id="viewDetailsModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="viewDetailsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="viewDetailsModalLabel">Plant Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-4">
            <img id="plant-image" src="" alt="Plant Image" class="img-fluid">
          </div>
          <div class="col-md-8">
            <h2 id="plant-name"></h2>
            <p id="plant-description"></p>
            <p id="plant-price"></p>
            <p id="plant-location"></p>
            <p id="seller-email"></p>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

</html>


    <script>
        

    // document.getElementById("chats").addEventListener("click", function() {
        
    // });
    // Function to handle clicking on the username
    $(document).on('click', '.chat-seller', function() {
    let userId = $(this).closest('.seller').data('user-id'); // Get user ID from the closest seller div

    // Fetch and display messages for this user
    // display_messages(userId);

    // Update the user status to 1 when chatting
    $.ajax({
        url: 'ajax/update_users_status.php', // Endpoint to update user status
        method: 'POST',
        data: {
            user_id: userId, // Send the user ID to update
            status: 1 // Set the status to 1
        },
        success: function(response) {
            console.log('User status updated successfully:', response);
        },
        error: function(xhr, status, error) {
            console.error('Error updating user status:', error);
        }
    });
});


   

   document.addEventListener('DOMContentLoaded', function() {

    $(document).on('click', '.chat-seller', function() {
    let sellerEmail = $(this).data('email');
    console.log(`Chat Seller button clicked: Seller Email=${sellerEmail}`); // Log the seller email

    // Redirect to chat page with seller email as a query parameter
    window.location.href = `chat_upgrade/chat.php?seller_email=${encodeURIComponent(sellerEmail)}`;
});

    // Check if the profile link exists (only when the user is logged in)
    const profileLink = document.querySelector('.profile-link');
    const chatsLink =document.getElementById('chats');
    
    if (profileLink) {
        profileLink.addEventListener('click', function() {
            const dropdownMenu = document.querySelector('.dropdown-profile');
            if (dropdownMenu) {
                dropdownMenu.classList.toggle('show');
            } else {
                console.error("Dropdown menu not found");
            }
        });
    } else {
        console.log("Profile link not found - User may not be logged in");
    }

    if (chatsLink) {
        chatsLink.addEventListener('click', function() {
            window.location.href = "chat_upgrade/index.php";
        });
    } else {
        console.log("Profile link not found - User may not be logged in");
    }

$(document).ready(function() {
    document.querySelectorAll('.chat-seller').forEach(button => {
    button.addEventListener('click', function() {
        // Get seller email from the data attribute
        const sellerEmail = this.getAttribute('data-email');

        // Redirect to chat.php with the seller email as a query parameter
        window.location.href = `chat_upgrade/chat.php?seller_email=${encodeURIComponent(sellerEmail)}`;
    });
});

    $(".about").click(function(event) {
  event.preventDefault();
  $.ajax({
    type: "GET",
    url: "aboutus.php",
    success: function(data) {
        console.log("Success: " + data);
      $("#contentContainer").html(data);
    },
    error: function(xhr, status, error) {
      console.error("Failed to load aboutus.php");
    }
  });
});
});

document.getElementById("about").addEventListener("click", function() {
    var featured = document.getElementById("featured");
    var newlyListed = document.getElementById("newlyListed");
    var aboutUs = document.getElementById("aboutUs");

    if (aboutUs) {
        featured.style.display = "none";
        newlyListed.style.display = "none";
        aboutUs.style.display = "block";
    } else {
        console.error("Element with id 'aboutUs' not found");
    }
});
document.getElementById("about1").addEventListener("click", function() {
    var featured = document.getElementById("featured");
    var newlyListed = document.getElementById("newlyListed");
    var aboutUs = document.getElementById("aboutUs");

    if (aboutUs) {
        featured.style.display = "none";
        newlyListed.style.display = "none";
        aboutUs.style.display = "block";
    } else {
        console.error("Element with id 'aboutUs' not found");
    }
});

document.getElementById("home").addEventListener("click", function() {
    var featured = document.getElementById("featured");
    var newlyListed = document.getElementById("newlyListed");
    var aboutUs = document.getElementById("aboutUs");

    if (aboutUs) {
        featured.style.display = "block";
        newlyListed.style.display = "block";
        aboutUs.style.display = "none";
    } else {
        console.error("Element with id 'aboutUs' not found");
    }
});

document.getElementById("home1").addEventListener("click", function() {
    var featured = document.getElementById("featured");
    var newlyListed = document.getElementById("newlyListed");
    var aboutUs = document.getElementById("aboutUs");

    if (aboutUs) {
        featured.style.display = "block";
        newlyListed.style.display = "block";
        aboutUs.style.display = "none";
    } else {
        console.error("Element with id 'aboutUs' not found");
    }
});



    // Hamburger menu functionality
    const hamburger = document.querySelector('.hamburger');
    if (hamburger) {
        hamburger.addEventListener('click', function() {
            const dropdownMenu = document.querySelector('.dropdown-menu');
            if (dropdownMenu) {
                dropdownMenu.classList.toggle('show');
            } else {
                console.error("Dropdown menu not found");
            }
        });
    } else {
        console.error("Hamburger menu not found");
    }
}); 
    // AJAX Fetching of top Seller
     $(document).ready(function() {
        


            $.ajax({
                url: 'Ajax/fetch_top_seller.php',
                type: 'GET',
                success: function(response) {
                    $('#featured-contents').html(response);

                    // Get the number of sellers from the hidden input
                    var numSellers = $('#num_sellers').val();

                    // Configure Splide depending on the number of sellers
                    if (numSellers > 1) {
                        // If more than 1 seller, use loop mode
                        new Splide('#seller-slider', {
                            type   : 'loop:slide',
                            perPage: 5,
                            autoplay: true,
                            gap: '1rem',
                            breakpoints: {
                                600: {
                                    perPage: 1,
                                },
                                900: {
                                    perPage: 2,
                                },
                            }
                        }).mount();
                    } else if (numSellers == 1) {
                        // If only 1 seller, use rewind mode (no looping)
                        new Splide('#seller-slider', {
                            type   : 'slide',
                            rewind : true,  // No loop, just slide back to the beginning
                            perPage: 1,     // Display 1 item
                            autoplay: false, // Disable autoplay
                        }).mount();
                    }
                }
            });
            // End of AJAX Fetching of top Seller

            // AJAX Fetching of newly listed plants
            $.ajax({ 
                url: 'Ajax/fetch_newly_listed.php',
                type: 'GET',
                success: function(response) {
                    try {
                        let plants = response;

                        if (plants.error) {
                            alert(plants.error); // Show error message if any
                            return;
                        }

                        // Group plants by location
                        let plantsByLocation = {};
                        plants.forEach(function(product) {
                            if (!plantsByLocation[product.location]) {
                                plantsByLocation[product.location] = [];
                            }
                            plantsByLocation[product.location].push(product);
                        });
                        // End of AJAX Fetching of newly listed plants

                        let contentHtml = '';
                        let locationsHtml = `
                            <div class="plant-location">
                                <button class="location-btn" data-location="all">Show All</button>
                            </div>`;

                        for (let location in plantsByLocation) {
                            // Add plant items to contentHtml
                            plantsByLocation[location].forEach(function(product) {
                                let imgPath = `Products/${product.seller_email}/${product.img1}`;
                                contentHtml += `
                                    <div class="plant-item" data-location="${product.location}">
                                        <div class="plant-image">
                                            <img src="${imgPath}" alt="${product.plantname}">
                                        </div>
                                        <p>${product.plantname}</p>
                                        <p>Price: ₱${product.price}</p>
                                        <div class="plant-item-buttons">
                                            <button class="view-details" data-id="${product.plantid}" data-email="${product.seller_email}">View Details</button>
                                            <button class="chat-seller" data-email="${product.seller_email}" >Chat Seller</button>
                                        </div>
                                    </div>`;
                            });

                            // Add location buttons to locationsHtml
                            locationsHtml += `
                                <div class="plant-location">
                                    <button class="location-btn" data-location="${location}">
                                        ${location}
                                    </button>
                                </div>`;
                        }

                        $('#newly-contents').html(contentHtml);
                        $('#locations').html(locationsHtml);

                        // Add event listeners to location buttons to filter plants
                        $('.location-btn').on('click', function() {
                            let location = $(this).data('location');
                            console.log(`Button clicked: Location=${location}`); // Log the location

                            if (location === 'all') {
                                $('.plant-item').show();
                            } else {
                                $('.plant-item').each(function() {
                                    if ($(this).data('location') === location) {
                                        $(this).show();
                                    } else {
                                        $(this).hide();
                                    }
                                });
                            }
                        });

                      // Add event listeners to view-details and chat-seller buttons
                      $(document).on('click', '.view-details', function() {
                            let plantId = $(this).data('id');
                            let sellerEmail = $(this).data('email');

                            console.log(`View Details button clicked: Plant ID=${plantId}`); // Log the plant ID.
                            console.log(`Chat Seller button clicked: Seller Email=${sellerEmail}`); // Log the seller email

                            // Create a hidden form and submit it
                            let form = $('<form>', {
                                action: 'viewdetails?plant=' + plantId,
                                method: 'POST'
                            }).append($('<input>', {
                                type: 'hidden',
                                name: 'plantId',
                                value: plantId
                            })).append($('<input>', {
                                type: 'hidden',
                                name: 'sellerEmail',
                                value: sellerEmail
                            }));

                            $('body').append(form);
                            form.submit();

                            // Push the current state into the history when opening the modal
                            history.pushState(null, '', window.location.href);
                        });

                        $(document).on('click', '.chat-seller', function() {
                            let sellerEmail = $(this).data('email');
                            console.log(`Chat Seller button clicked: Seller Email=${sellerEmail}`); // Log the seller email
                            window.location.href = `chat_upgrade/chat.php?seller_email=${encodeURIComponent(sellerEmail)}`;
                        });
                    } catch (e) {
                        console.error("Error parsing JSON", e);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", status, error);
                    Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "An unexpected error occurred. Please try again later."
                });
                } 
                             
            });  

            
            // End of AJAX Fetching of newly listed plants
            $('#viewDetailsModal .close').on('click', function() {
            $('#viewDetailsModal').modal('hide');
            });

// Login form validation
$("#loginForm").submit(function(event) {
    event.preventDefault();

    var email = $("#loginEmail").val();
    var password = $("#loginPassword").val();

    $.ajax({
        url: "Ajax/login.php",
        type: "POST",
        data: { email: email, password: password },
        dataType: 'json',
        success: function(response) {
            console.log("Response: " + JSON.stringify(response));
            if (response.success) {
                Swal.fire({
                    position: "center",
                    icon: "success",
                    title: response.message,
                    showConfirmButton: false,
                    timer: 3000
                });
                // Reload page after 1.5 seconds
                setTimeout(function() {
                    location.reload();
                }, 3000);
            } else {
                if (response.message === 'Email not found') {
                    $("#loginEmail").addClass("error");
                    $("#loginEmail").next(".error-label").text(response.message).show();
                } else if (response.message === 'Invalid password') {
                    $("#loginPassword").addClass("error");
                    $("#loginPassword").next(".error-label").text(response.message).show();
                } else {
                    $("#loginEmail").addClass("error");
                    $("#loginPassword").addClass("error");
                    $(".error-label").text(response.message).show();
                }
            }
        },
        error: function(xhr, status, error) {
            if (xhr.status === 200) {
        // Login was successful, but response body is not in expected format
        console.log("Login successful, but response body is not in expected format");
        // You can also try to parse the response body as text or HTML
        }else{
            console.error("Error: " + status + " - " + error);
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "An unexpected error occurred. Please try again later.",
            });
            $("#loginEmail").addClass("error");
            $("#loginPassword").addClass("error");
            $(".error-label").text("An unexpected error occurred").show();
        }
    }
    });
});

// Clear error label when correct email is inputted
$("#loginEmail").on("keyup", function() {
    var email = $(this).val();
    if (email !== "") {
        $(this).removeClass("error");
        $(this).next(".error-label").hide();
    }
});
$("#signupForm").submit(function(event) {
    event.preventDefault(); // Prevent default form submission

    // Create a FormData object
    var formData = new FormData();

    // Append form data
    formData.append('profilePicture', $('#signupProfilePicture')[0].files[0]); // Get the file input
    formData.append('email', $("#signupEmail").val());
    formData.append('password', $("#signupPassword").val());
    formData.append('firstname', $("#signupFirstName").val());
    formData.append('lastname', $("#signupLastName").val());
    formData.append('gender', $("#signupGender").val());
    formData.append('phonenumber', $("#signupPhoneNumber").val());
    formData.append('address', $("#signupAddress").val());

    $.ajax({
        url: "Ajax/register.php",
        type: "POST",
        data: formData,
        processData: false, // Prevent jQuery from processing the data
        contentType: false, // Prevent jQuery from setting the content-type
        success: function(response) {
            console.log("Response: " + response);
            if (response.trim() === "success") {
                Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "Successfully Registered",
                    showConfirmButton: true,
                    timer: 3000
                });
                // Reload page after 3 seconds
                setTimeout(function() {
                    location.reload();
                }, 3000);
            } else {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "An unexpected error occurred. Please try again later."
                });
            }
        },
        error: function(xhr, status, error) {
            console.error("Error: " + status + " - " + error);
            Swal.fire({
                icon: "error",
                title: "Error",
                text: "An unexpected error occurred. Please try again later."
            });
        }
    });
});



// Logout AJAX
$(document).on('click', '#logoutLink', function(event) {
        event.preventDefault();

        $.ajax({
            url: 'Ajax/logout.php', // Path to your logout.php file
            type: 'POST',
            success: function(response) {
                if (response.trim() === "success") {
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: 'Successfully Logged out',
                        showConfirmButton: false,
                        timer: 3000
                    });
                    // Reload page after 3 seconds
                    setTimeout(function() {
                        location.reload();
                    }, 3000);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Logout Failed',
                        text: 'Please try again.',
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error("Error: " + status + " - " + error);
                Swal.fire({
                icon: "error",
                title: "Error",
                text: "An unexpected error occurred. Please try again later."
            });
            }
        });
    });

    // Add event listener to view-details buttons

        // Get the modals
    var loginModal = document.getElementById("loginModal");
    var signupModal = document.getElementById("signupModal");

    // Get the links to open the modals
    var signupLink = document.getElementById("signupLink");
    var loginLink = document.getElementById("loginLink");

    // Get the links inside modals (Login inside Signup modal and vice versa)
    var loginLinkInSignupModal = document.querySelector("#signupModal #loginLink");

    // Get the <span> elements that close the modals
    var closeButtons = document.getElementsByClassName("close");

    // Function to open the login modal
    function openLoginModal() {
        signupModal.style.display = "none";
        loginModal.style.display = "block";
    }

    // Function to open the signup modal
    function openSignupModal() {
        loginModal.style.display = "none";
        signupModal.style.display = "block";
    }

    // When the user clicks the signup link, open the signup modal
    signupLink.onclick = function(event) {
        event.preventDefault();
        openSignupModal();
        loginForm.reset();
    };

    // When the user clicks the login link, open the login modal
    loginLink.onclick = function(event) {
        event.preventDefault();
        openLoginModal();
        signupForm.reset();
    };

    loginLink1.onclick = function(event) {
        event.preventDefault();
        openLoginModal();
        signupForm.reset();
    };

    // When the user clicks the login link inside the signup modal, switch to the login modal
    loginLinkInSignupModal.onclick = function(event) {
        event.preventDefault();
        openLoginModal();
        signupForm.reset();
    };

    // Close the modals when clicking the close (x) buttons
    for (var i = 0; i < closeButtons.length; i++) {
        closeButtons[i].onclick = function() {
            loginModal.style.display = "none";
            signupModal.style.display = "none";
        };
    }

    // // Close the modal if the user clicks outside of it
    // window.onclick = function(event) {
    //     if (event.target == loginModal) {
    //         loginModal.style.display = "none";
    //     }
    //     if (event.target == signupModal) {
    //         signupModal.style.display = "none";
    //     }
    // };
    
     
    });

    </script>
</body>
</html>