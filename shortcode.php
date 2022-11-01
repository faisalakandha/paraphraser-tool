<?php

function paraphraser()
{

    ob_start();
?>

    <!DOCTYPE html>

    <html>

    <head>

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js" integrity="sha384-ODmDIVzN+pFdexxHEHFBQH3/9/vQ9uori45z4JjnFsRydbmQbmL5t1tQ0culUzyK" crossorigin="anonymous"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>

        <script type="text/javascript">
            var globe = 0;

            var omVAL = "Fluency";
            //OBJECT Mapper
            function objectMapper(value) {
                var array = ["Word Changer", "Fluency", "Advanced", "Creative"];
                for (var i = 0; i < array.length; i++) {
                    if (array[i] === value) return i;
                }
            }

            // Menu 
            $("document").ready(function() {
                var myInterval;
                $("#loadingSpinner").hide();
                $("#defaultItem").addClass("active");
                $(".item").click(function() {
                    $(".item").removeClass("active");
                    $(this).addClass("active");
                    omVAL = $(this).text();
                });

                // Text area input
                var $input = $('#input-content');
                var $langSelected = $('#selectLang');

                function CreateLoading() {
                        myElement = $("#loading-count");
                        myElement.text(globe + "%");
                }

                $("#textSubmit").click(function() {
                    $("#loadingSpinner").show();
                    var data = {
                        data: $input.val(),
                        lang: $langSelected.val(),
                        mode: objectMapper(omVAL),
                        style: 0
                    };

                    async function postData(data) {
                        $("#app-container").css("opacity", 0.3);
                        myInterval = setInterval(() => {
                            globe += 1;
                            CreateLoading();
                        }, 2000)

                        return fetch('http://127.0.0.1/wp-json/paraphraser/v1/paraphrased', {
                                method: 'POST', // or 'PUT',
                                mode: 'cors', // no-cors, *cors, same-origin
                                headers: {
                                    'Content-Type': 'application/json',

                                },
                                body: JSON.stringify(data),
                            })
                            .then((response) => {
                                response.text();
                                while (globe <= 100) {
                                    globe++;
                                    CreateLoading();
                                }
                            })
                            .then(function(data) {
                                $("#output-content").val(data.substring(1, data.length - 1));
                                $("#loadingSpinner").hide();
                                $("#app-container").css("opacity", 1);
                                clearInterval(myInterval);
                            })
                            .catch((error) => {
                                console.error('Error:', error);
                            });
                    }

                    postData(data);

                });

            });
        </script>
        <style>
            .upload-btn-wrapper {
                position: relative;
                overflow: hidden;
                display: inline-block;
                padding-left: 15px;
                cursor: pointer;
            }

            .btn {
                border: none;
                color: black;
                background-color: white;
                padding: 0px 0px 0px 0px;
                border-radius: 8px;
                font-size: 16px;
                cursor: pointer;
            }

            .upload-btn-wrapper input[type=file] {
                font-size: 16px;
                position: absolute;
                left: 0;
                top: 0;
                opacity: 0;
                cursor: pointer;
            }

            .paraphraser_option_box {
                position: relative;
                border: 1px solid #ddd;
                display: felx;
                background: #fdf9f9c7;
                padding: 10px 10px 10px 10px;
                justify-content: flex-start;
                flex-wrap: wrap;
                font-family: Roboto, sans-serif;
                width: 100%;
            }

            #input-content,
            #output-content {
                height: 346px;
                font-size: 16px;
                width: 99%;
                border: none;
                border-top-style: none;
                background-color: white;
            }

            .item {
                padding-left: 5px;
                padding-right: 5px;
                display: inline-block;
                font-family: Poppins, sans-serif;
                font-size: 16px;
                color: #000;
                cursor: pointer;
                margin-right: 5px;
                font-weight: 550;
            }

            .active,
            .item:hover {
                border-bottom: 3px solid violet;

            }

            textarea {
                border: none;
                background-color: transparent;
                resize: none;
                outline: none;
                padding: 20px;
            }
        </style>
    </head>

    <body>
        <div id="app-container" class="row">

            <div class="col-lg-6">
                <form style="margin-left: 13px; border: solid 1px #ddd; padding: 0px 0px 5px 0px; margin-top: 20px;">


                    <div id="topMenu" class="form-group paraphraser_option_box justify-content-start">
                        <!-- <span class="item">Word Changer</span> -->
                        <span id="defaultItem" class="item">Fluency</span>
                        <img data-src="https://www.paraphraser.io/images/pro.png" style="vertical-align: baseline;" src="https://www.paraphraser.io/images/pro.png" class="loaded"><span class="item">Advanced</span>
                        <img data-src="https://www.paraphraser.io/images/pro.png" style="vertical-align: baseline;" src="https://www.paraphraser.io/images/pro.png" class="loaded"><span class="item">Creative</span>
                    </div>



                    <div class="form-group">
                        <textarea placeholder="Enter your input text here..." name="input_content" id="input-content"></textarea>
                        <div class="upload-btn-wrapper">
                            <img data-src="https://www.paraphraser.io/images/file.png" alt="Upload" width="24" src="https://www.paraphraser.io/images/file.png">
                            <button class="btn">Upload a file</button>
                            <input type="file" name="myfile" />
                        </div>
                        <div size="1" autocomplete="off" style="display: inline-block; float:right; padding-right: 15px;">
                            <select id="selectLang" name="languages" id="langs">
                                <option value="en">English</option>
                                <option value="es">Espanol</option>
                                <option value="no">Norwegian</option>
                                <option value="nl">Dutch</option>
                                <option value="fr">French</option>
                                <option value="de">Germany</option>
                                <option value="br">Portugues</option>
                                <option value="tr">Turkish</option>
                                <option value="id">Indonesian</option>
                                <option value="ru">Russian</option>
                                <option value="ja">Japnese</option>
                                <option value="zh">Chinese</option>
                                <option value="sv">Swedish</option>
                                <option value="ro">Romanian</option>
                                <option value="vi">Vietnamese</option>
                                <option value="fa">Persian</option>
                            </select>
                        </div>
                    </div>
                </form>

            </div>
            <div class="col-lg-6">
                <div style="border: solid 1px #ddd; margin-top: 20px; padding-bottom: 45px; margin-right: 13px;">
                    <div class="paraphraser_option_box">
                        Paraphrased Text
                    </div>
                    <textarea style="padding-top: 16px;" id="output-content" disabled placeholder="Paraphrased text will be shown here...." class="form-control"></textarea>
                </div>
            </div>
        </div>
        <center>
            <div id="textSubmit"><input type="button" value="Submit" style="margin-top: 20px; border:none; background-color:violet; height: 40px; width: 150px; font-size: 20px; font-weight: 500px; 
                                                                                    border-radius: 10px;">
                <div id="loadingSpinner" class="spinner-border text-primary" role="status">
                    <span id="loading-count"></span>
                </div>
            </div>
        </center>
        </div>

    </body>

    </html>

<?php
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}

add_shortcode('paraphraser_shortcode', 'paraphraser');

?>