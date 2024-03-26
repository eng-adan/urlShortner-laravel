<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet"  href="{{ asset('/css/app.css') }}">





    <title>URL Shortener</title>

</head>
<body>    
    <div class="containers">
        <div class="main_heading">
            <h1 class="title"> <span class="url_shortner">URL Shortener</span> & <span class="pdf_converter">Pdf Converter</span> </h1>
        </div>
        
    </div>
    <div class="container">
        <div class="tab">
            
        </div>
        <div id="shortner_container" class="tabcontent">
            <div>
                <h1 id="h1">URL Shortener</h1>
            </div>
            <div class="input-group">
                <input type="text" id="url_input" placeholder="Enter URL">
                <button onclick="submitUrl()">Shorten URL</button>
            </div>
            <div class="input-group">
                <input type="text" id="my_url" readonly placeholder="Short Url">
                <button class="copy-button" onclick="copyUrl()">Copy</button>
            </div>
        </div>
    
    </div>
    <div class="container">
        <div class="tab">
        </div>
        <div id="shortner_container" class="tabcontent">
            <div>
                <h1 class="h1">Document Converter</h1>
            </div>
            <div class="input-group">
                <input type="file" id="word_file" accept=".docx">
                <button onclick="convertToPDF()">Convert to PDF</button>
            </div>
            <div class="input-group" id="pdf_download" style="display: none;">
                <a id="download_link" download="converted_document.pdf">
                    <button>Download PDF</button>
                </a>
            </div>
        </div>
       
    </div>
    <div class="container custom_class">
        <h2>Shorten, share and track</h2>
        <p>Your shortened URLs can be used in publications, documents, advertisements, blogs, forums, instant messages, and other locations. Track statistics for your business and projects by monitoring the number of hits from your URL with our click counter.
        </p>
    </div>
    <div id="box">
        <div id="row" class="row">
            <div id="column" class="col-md-4">
                <div class="icon"><img src="{{ asset('img/icon-like.png') }}"></div>
                <h3 class="aligncenter">Easy</h3>
                <p class="aligncenter">ShortURL is easy and fast, enter the long link to get your shortened link</p>
            </div>
            <div id="column">
                <div class="icon"><img src="{{ asset('img/icon-url.png') }}"></div>
                <h3 class="aligncenter">Shortened</h3>
                <p class="aligncenter">Use any link, no matter what size, ShortURL always shortens</p>
            </div>
            <div id="column">
                <div class="icon"><img src="{{ asset('img/icon-secure.png') }}"></div>
                <h3 class="aligncenter">Secure</h3>
                <p class="aligncenter">It is fast and secure, our service has HTTPS protocol and data encryption</p>
            </div>
            <div id="column">
                <div class="icon"><img src="{{ asset('img/icon-statistics.png') }}"></div>
                <h3 class="aligncenter">Statistics</h3>
                <p class="aligncenter">Check the number of clicks that your shortened URL received</p>
            </div>
            <div id="column">
                <div class="icon"><img src="{{ asset('img/icon-unique.png') }}"></div>
                <h3 class="aligncenter">Reliable</h3>
                <p class="aligncenter">All links that try to disseminate spam, viruses and malware are deleted</p>
            </div>
            <div id="column">
                <div class="icon"><img src="{{ asset('img/icon-responsive.png') }}"></div>
                <h3 class="aligncenter">Devices</h3>
                <p class="aligncenter">Compatible with smartphones, tablets and desktop</p>
            </div>
        </div>
    </div>
    

    <footer class="footer mt-5">
        <div class="">
            <p>&copy; 2024 DevsSpace. All rights reserved.</p>
        </div>
    </footer>

    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mammoth/1.4.4/mammoth.browser.min.js"></script>



<script>
        function submitUrl() {
            var longUrl = document.getElementById("url_input").value;
            
            if (!longUrl.trim()) {
                alert("Please enter a URL.");
                return; // Exit the function if the input is empty
            }

            var shortCode = Math.random().toString(36).substring(7);
            var shortUrl = window.location.origin + "/" + shortCode;
            document.getElementById("my_url").value = shortUrl;
            var newUrl = shortCode;
            
            // Retrieve CSRF token value from the meta tag
            var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            console.log(csrfToken);

            $.ajax({
                url: window.location.origin + '/short-url', // Dynamic server URL based on current window location
                type: 'POST',
                contentType: 'application/json',
                headers: {
                    'X-CSRF-TOKEN': csrfToken // Include CSRF token in headers
                },
                    data: JSON.stringify({ url: longUrl, shortUrl: newUrl }),
                success: function(response) {

                },
                error: function() {
                    // alert("Error: Unable to generate short URL.");
                }
            });
        }

        function copyUrl() {
            var myUrlInput = document.getElementById("my_url");
            myUrlInput.select();
            document.execCommand("copy");
            alert("Copied to clipboard: " + myUrlInput.value);
        }

     

        function convertToPDF() {
            var inputElement = document.getElementById("word_file");
            console.log(inputElement.files);
            var wordFile = inputElement.files[0];
            console.log(wordFile);
            
            if (!wordFile) {
                alert("Please select a Word file.");
                return;
            }

            var reader = new FileReader();
            reader.onload = function(event) {
                var arrayBuffer = event.target.result;

                mammoth.convertToHtml({ arrayBuffer: arrayBuffer })
                    .then(function(result) {
                        var html = result.value;
                        var element = document.createElement('div');
                        element.innerHTML = html;
                        
                        // Convert HTML to PDF
                        html2pdf(element, {
                            margin:       1,
                            filename:     'converted_document.pdf',
                            image:        { type: 'jpeg', quality: 0.98 },
                            html2canvas:  { dpi: 192, letterRendering: true },
                            jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' }
                        });
                    })
                    .catch(function(err) {
                        console.error("Error converting Word to HTML: ", err);
                        alert("Error converting Word to PDF. Please try again.");
                    });
            };

            reader.readAsArrayBuffer(wordFile);
        }
        function openTab(evt, tabName) {
                    var i, tabcontent, tablinks;
                    tabcontent = document.getElementsByClassName("tabcontent");
                    for (i = 0; i < tabcontent.length; i++) {
                        tabcontent[i].style.display = "none";
                    }
                    tablinks = document.getElementsByClassName("tablinks");
                    for (i = 0; i < tablinks.length; i++) {
                        tablinks[i].className = tablinks[i].className.replace(" active", "");
                    }
                    document.getElementById(tabName).style.display = "block";
                    evt.currentTarget.className += " active";
                }

</script>
</body>
</html>
