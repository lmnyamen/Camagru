(function(){
    var video = document.getElementById('video'),
        canvas = document.getElementById('canvas'),
        context = canvas.getContext('2d'),
        photo = document.getElementById('photo'),
        sticker_1 = document.getElementById('photo');
    

    navigator.mediaDevices.getUserMedia({ video : true, Audio : false}) 
        .then(function(stream){
            video.srcObject = stream;
            video.play();
        })
        .catch(function(errors){
            console.log("An error occurred: " + errors);
        })

    function uploadfile(blob) {
        const form = new FormData();

        form.append('image', blob);
        form.append('json', 'true');
        form.append('upload', 'true');

        
        // 1. Create a new XMLHttpRequest object
        let xhr = new XMLHttpRequest();

        // 2. Configure it: GET-request for the URL /article/.../load
        xhr.open('POST', 'images/upload.php');

        // 3. Send the request over the network
        xhr.send(form);

        // 4. This will be called after the response is received
        xhr.onload = function() {
            // console.log(xhr.responseText);
            const res = JSON.parse(xhr.responseText);
            console.log(res.result);
            if (xhr.status != 200) { // analyze HTTP status of the response
                // alert(`Error ${xhr.status}: ${xhr.statusText}`); // e.g. 404: Not Found
            } else { // show the result
                // alert(`Done, got ${xhr.response.length} bytes`); // responseText is the server
            }
        };
    }
    document.getElementById('capture').addEventListener('click', function(){
        context.drawImage(video, 0, 0, 400, 300);
        context.drawImage(document.getElementById('supImage'), 0, 0, 100, 50);
        context.drawImage(document.getElementById('supImage1'), 0, 0, 100, 50);
        //photo.setAttribute('src', canvas.toDataURL('image/png'));
        // console.log(canvas.toDataURL('image/png'));
        canvas.toBlob(function(blob) {
            uploadfile(blob);
        });
    })
}) ();