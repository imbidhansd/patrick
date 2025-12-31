<html>
    <head>
        <style>
            body{
                padding-bottom:0px;
                margin-bottom:0px;
                margin-top:0px;
                padding-top:0px;
            }
            h2{
                padding-bottom:0px;
                margin-bottom:0px;
                margin-top:5px;
                padding-top:0px;
                font-family: Arial;
                font-size:200%;
                color:green;
            }
            p{
                padding-bottom:0px;
                margin-bottom:5px;
                margin-top:10px;
                padding-top:5px;
                font-family: Arial;
                font-size:100%;
                font-weight:normal;

            }
            .inp{
                padding-bottom:0px;
                margin-bottom:0px;
                margin-top:5px;
                padding-top:0px;
                font-family: Arial;
                font-size:100%;
                width:100%;
                border-bottom:1px solid #000000;
                background-color:#dedede;
            }
            fieldset{

                padding-bottom:0px;
                margin-bottom:0px;
                margin-top:5px;
                padding-top:0px;
                border: 1px solid #000000;
                padding:10px;
                font-family: Arial;
                font-size:150%;
                font-weight:bold;
                margin-top:10px;
            }

            textarea{
                font-family: Arial;
                font-size:100%;
                border:2px solid #000000;
                background-color: #dadada;
                background-color:#dedede;
            }

        </style>
    </head>
    <body>

    <htmlpageheader name="page-header">
        <p style="font-size: 12px; text-align: right;">Page No:{PAGENO}</p>
        <table class="table" border="0">
            <tr>
                <td colspan="2" class="text-center">
                    <img src="{{ asset('/images/header-logo.png') }}" alt="{{ env('SITE_TITLE') }}" />
                </td>
            </tr>
        </table>
    </htmlpageheader>

    <h2>Basic PDF Active Form</h2>
    <form action="#" method="post">
        <fieldset>Personal Information
            <p>Name:</p>
            <div class="inp"><input type="text" size="200" name="inputname" value="" title="Enter your name"/> </div>
            <p>Company:</p>
            <div class="inp"><input type="text" size="200" name="inputco" value="" title="Company name"/> </div>
            <p>Email:</p>
            <div class="inp"><input type="text" size="200" name="inputemail" value="" title="Email address"/> </div>
            <p>Mobile:</p>
            <div class="inp"><input type="text" size="200" name="inputmobile" value="" title="Mobile telephone number"/> </div>
        </fieldset>

        <fieldset>Additional Information

            <p>How many languages do you speak?</p> 

            <input type="radio" name="num_languages" value="1" checked="checked" > One      
            <input type="radio" name="num_languages" value="2" > Two      
            <input type="radio" name="num_languages" value="3" > Three      
            <input type="radio" name="num_languages" value="4" > Four      
            <input type="radio" name="num_languages" value="5" > Five +      

            <br /><br />


            <input type="checkbox" name="email" value="email_subscrbe" checked="checked" /> Please send me regular information by e-mail<br>
            <input type="checkbox" name="subscribe" value="newsletter_subscribe" /> Please subscribe me to the Newsletter

            <p>Seminar Information</p> 
            <label for="reg_seminar">Seminar</label>
            <select name="reg_seminar" id="reg_seminar">
                <option value="Guitar for Beginners" >Guitar for Beginners</option>
                <option value="JavaScript Today" >JavaScript Today</option>
                <option value="Time is on our side" >Time is on our side</option>
            </select>

            <label for="reg_location">Location</label>
            <select name="reg_location" id="reg_location">
                <option value="New York" >New York</option>
                <option value="Paris" >Paris</option>
                <option value="Munich" >Munich</option>
                <option value="Gartmorn" >Gartmorn</option>
            </select>

            <p>Payment Type</p>
            <label><input type="radio" name="regpay" id="reg_payment01" value="Voucher" >
                Voucher</label>

            <label><input type="radio" name="regpay" id="reg_payment02" value="Cash" >
                Cash</label>

            <label><input type="radio" name="regpay" id="reg_payment03" value="CreditCard" >
                Credit Card</label>

            <label><input type="radio" name="regpay" id="reg_payment04" value="BitCoin" >
                BitCoin</label>

            <p>Comments:</p>
            <textarea name="textareafield"  rows="4" cols="80" wrap="virtual"></textarea>
        </fieldset>

    </form>
</body>
</html>
