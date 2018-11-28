
    <!DOCTYPE html>

    <html lang="en">

    <head>

    <meta charset="UTF-8">

    <title>jQuery Get Real Image Dimensions</title>

    <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>

    <script type="text/javascript">

    	var realWidth;

	    var realHeight;

    $(document).ready(function(){


            var img = $("#imagem");

            // Create dummy image to get real width and height

            $("<img>").attr("src", $(img).attr("src")).load(function(){

                realWidth = this.width;

                realHeight = this.height;

                $("#svg").removeAttr('viewBox');
                $("#svg")[0].setAttribute('viewBox', "0 0 " + realWidth + " " + realHeight);

                $("#svg_image").attr("width", realWidth);
                $("#svg_image").attr("height", realHeight);

            });


    });

    </script>

    </head>

    <body>


        <img src="img/1ad5be0d.jpg" id="imagem" style="display:none">

        <svg id="svg" width="100%" height="auto" viewBox="0 0 100 100" version="1.1"
		     xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
			<image id="svg_image" xlink:href="img/1ad5be0d.jpg" x="0" y="0" height="100" width="100"/>
			<rect stroke="red" stroke-width="2" x="190" y="446" width="129" height="142" fill="transparent"/>
		</svg>

    </body>

    </html>

