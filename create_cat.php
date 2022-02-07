<?php
include 'head-foot/header.php';
?>
<div id="formular"  class="form-box">
<form name="form-check">
    <div class="form-group">
        <label for="exampleFormControlInput1">Názov kategórie</label>
        <input type="text" class="form-control" name="cat_name" id="exampleFormControlInput1">
    </div>
    <div class="form-group">
        <label for="exampleFormControlTextarea1">Popis kategórie</label>
        <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="cat_description"></textarea>
    </div>
    <input id="odosli" name="submit" type="button" class="btn btn-primary" value="Submit">
</form>
</div>


<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script>
    $(function () {
        //$("form").validate();

        $('#odosli').click(function (e) {
            // using this page stop being refreshing
            event.preventDefault();

            var empt = document.forms["form-check"]["cat_name"].value;
            if (empt == "")
            {
                alert("Názov nesmie byť prázdny!");
                return false;
            }
            empt = document.forms["form-check"]["cat_description"].value;
            if (empt == "")
            {
                alert("Popis nesmie byť prázdny!");
                return false;
            }

            $.ajax({
                type: 'POST',
                url: 'forum/category_add.php',
                data: $('form').serialize(),
                success: function () {
                    alert('form was submitted');
                    window.location.replace("index.php");
                }
            });
        });
    });
</script>

<?php
include 'head-foot/footer.php';
?>
