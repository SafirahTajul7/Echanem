function passwdVisibility(passwordFieldId, toggleIconSelector) {
    const passwordField = document.getElementById(passwordFieldId);
    const togglePasswordIcon = document.querySelector(toggleIconSelector + " i");
  
    togglePasswordIcon.addEventListener("click", function () {
        event.stopPropagation();
        if (passwordField.type === "password") {
            passwordField.type = "text";
            togglePasswordIcon.classList.remove("fa-eye");
            togglePasswordIcon.classList.add("fa-eye-slash");
        } else {
            passwordField.type = "password";
            togglePasswordIcon.classList.remove("fa-eye-slash");
            togglePasswordIcon.classList.add("fa-eye");
        }
    });
}

function editMember(id, firstname, lastname, email, phone, address) {
    document.getElementById('edit_id').value = id;
    document.getElementById('firstname').value = firstname;
    document.getElementById('lastname').value = lastname;
    document.getElementById('email').value = email;
    document.getElementById('phone').value = phone;
    document.getElementById('address').value = address;
}


function editProduct(id, name, description, price, stock, sizes, category, gendertype, image_url) {
    document.getElementById('edit_id').value = id;
    document.getElementById('name').value = name;
    document.getElementById('description').value = description;
    document.getElementById('price').value = price;
    document.getElementById('stock').value = stock;
    document.getElementById('sizes').value = sizes;
    document.getElementById('category').value = category;
    document.getElementById('gtype').value = gendertype;
    document.getElementById('image_url').value = image_url;
}


function openCloseModal(modalId, buttonId, closeClass) {
    const modal = document.getElementById(modalId);
    const btn = document.getElementById(buttonId);
    const closeElements = document.getElementsByClassName(closeClass);
  
    btn.addEventListener('click', function() {
        modal.style.display = "block";
    });
  
    for (const closeElement of closeElements) {
        closeElement.addEventListener('click', function() {
            modal.style.display = "none";
        });
    }

}


function fetchProducts() {
    var filters = {
        search: $('#search').val(),
        gender: [],
        category: [],
        size: []
    };

    $('.filter:checked').each(function() {
        if ($(this).attr('name') === 'gender') {
            filters.gender.push($(this).val());
        } else if ($(this).attr('name') === 'category') {
            filters.category.push($(this).val());
        } else if ($(this).attr('name') === 'size') {
            filters.size.push($(this).val());
        }
    });

    $.ajax({
        url: 'fetch_products.php',
        method: 'GET',
        data: filters,
        success: function(response) {
            $('#product-catalog').html(response);
        }
    });
}



function gotoProdDetail(productId) {
    window.location.href = 'product_detail.php?id=' + productId;
}


function addToCartCatalogue(productId) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'handler/cus_cart_add.php', true);
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
        if (xhr.status === 200) {
            alert('Product added to cart successfully!');
        } else {
            alert('Failed to add product to cart. Please try again.');
        }
    };
    xhr.send('product_id=' + productId);
}


function addToCartDetail(productId, stockElement) {
    const sizeSelect = document.getElementById('size-select');
    const selectedSize = sizeSelect ? sizeSelect.value : '';
    const quantity = parseInt(document.getElementById('quantity').value);
    const stock = stockElement;

    if (!selectedSize) {
        alert('Please select a size.');
        return;
    }

    // Check if user input is greater than stock number
    if (quantity > stock) {
        alert('Your quantity exceeds available stock. Please try again');
        return;
    }

    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'handler/cus_cart_add.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            alert(response.message);
            if (response.success) {
                window.location.href = 'cart.php';
            }
        } 
        // Go to Sign In page if not logged in yet.
        else {
            window.location.href = '../access/login.php';
        }
    };
    xhr.send(`product_id=${productId}&size=${selectedSize}&quantity=${quantity}`);
}


function buyNow(productId, stockElement) {
    const size = document.getElementById('size-select').value;
    const quantity = parseInt(document.getElementById('quantity').value);
    const stock = stockElement;

    if (quantity > stock) {
        alert('Your quantity exceeds available stock. Please try again');
        return;
    }

    window.location.href = 'checkout.php?id=' + productId + '&size=' + size + '&quantity=' + quantity;
}

function validatePassword() {
    var password = document.getElementById("password").value;
    var passwordError = document.getElementById("password-error");
    var regex = /^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*?])[A-Za-z\d!@#$%^&*?]{8,}$/;
    if (!regex.test(password)) {
        passwordError.style.display = "block";
        return false;
    }
    passwordError.style.display = "none";
    return true;
}

// Hello1234@