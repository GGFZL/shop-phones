function deletePhone(phoneID) {
    if (confirm('Are you sure you want to delete this phone?')) {
        $.ajax({
            url: 'models/phones/deletePhone.php',
            method: 'POST',
            data: { phoneID: phoneID },
            success: function(response) {
                if (response.success) {
                    $('#phone-row-' + phoneID).remove();
                    alert('Phone deleted successfully.');
                } else {
                    alert('Failed to delete phone.');
                }
            },
            error: function() {
                alert('An error occurred while deleting the phone.');
            }
        });
    }
}

$(document).ready(function() {
    //update fillined form
    $('.update-phone-btn').click(function() {
        var phoneData = $(this).data('phone');

        $('#updatePhoneId').val(phoneData.ID_phone);
        $('#updatePhoneName').val(phoneData.name);
        $('#updatePhonePrice').val(phoneData.Price);
        $('#updatePhoneDescription').val(phoneData.Description);
        $('#updatePhoneFeatured').val(phoneData.Featured);

        var manufacturerName = phoneData.manufacturer_name;
        $('#updatePhoneManufacturer option').filter(function() {
            return $(this).text() === manufacturerName;
        }).prop('selected', true);

        var colors = $('#phone-row-' + phoneData.ID_phone).data('colors');
        
        $('.update-phone-color2').prop('checked', false);

        if (colors) {
            $('.update-phone-color2').each(function() {
                var colorName = $(this).next().text().trim();
                if (colors.includes(colorName)) {
                    $(this).prop('checked', true);
                }
            });
        }
    });
    //end filled form update

    //database update
    $('#updatePhoneForm').submit(function(event) {
        event.preventDefault();
        var formData = $(this).serialize();
        
        $.ajax({
            type: 'POST',
            url: 'models/phones/updatePhone.php',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#updatePhoneModal').modal('hide');
                    alert(response.message);
                    location.reload();
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });
    //end database update

    //surveys
    var answerIndex = 4;

    function addAnswerField() {
        var answerField = `
            <div class="form-group">
                <label for="answer${answerIndex}">Additional Answer ${answerIndex - 1}:</label>
                <input type="text" id="answer${answerIndex}" name="answers[]" class="form-control" required>
            </div>
        `;
        $('#answers-container').append(answerField);
        answerIndex++;
    }

    $('#add-answer-btn').click(function() {
        addAnswerField();
    });
    // end-surveys

    $(document).ready(function() {
        $('.chbCSS').change(function() {
            $('#filterForm').submit();
        });
    });
    
    // function updateContent() {
    //     var selectedBrands = [];
    //     var selectedColors = [];
    //     document.querySelectorAll('input[name="brands[]"]:checked').forEach(function(checkbox) {
    //         selectedBrands.push(checkbox.value);
    //     });
    //     document.querySelectorAll('input[name="colors[]"]:checked').forEach(function(checkbox) {
    //         selectedColors.push(checkbox.value);
    //     });
        
    //     fetch(`index.php?page=shop?brands=${selectedBrands.join(',')}&colors=${selectedColors.join(',')}`)
    //     .then(response => {
    //       if (!response.ok) {
    //         throw new Error('Network response was not ok');
    //       }
    //       return response.text();
    //     })
    //     .then(data => {
    //       document.getElementById('phoneDataContainer').innerHTML = data;
    //     })
    //     .catch(error => {
    //       console.error('There was a problem with the fetch operation:', error);
    //     });
      
    // };
    
    /*buy button*/
    document.querySelectorAll('.buy-now').forEach(function(button) {
        button.addEventListener('click', function() {
            var name = this.getAttribute('data-name');
            var image = this.getAttribute('data-image');
            var price = this.getAttribute('data-price');
            var colors = this.getAttribute('data-colors').split(', ');
    
            var phoneDetailsHtml = `
                <img src="assets/images/${image}" alt="${name}" class="img-fluid">
                <h5>${name}</h5>
                <p>Price: ${price}(RSD)</p>
            `;
            document.querySelector('.phone-details').innerHTML = phoneDetailsHtml;
    
            var colorOptionsHtml = '<p>Choose color:</p>';
            colors.forEach(function(color) {
                colorOptionsHtml += `
                    <label class="color-option">
                        <input type="radio" name="color" value="${color}">
                        <span class="color-dot" style="background-color: ${color};"></span>
                    </label>
                `;
            });
            document.querySelector('.color-options').innerHTML = colorOptionsHtml;
    
            $('#phoneModal').modal('show');
        });
    });
    
    document.querySelector('.finish-shopping').addEventListener('click', function() {
        alert('Thank you for shopping!');
        $('#phoneModal').modal('hide');
    });
});