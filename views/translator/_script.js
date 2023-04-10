$(document).ready(function () {
	hideSpinner();
});

$('.btn-upload').click(function (e) {
	e.preventDefault();

	// Form submit
	var data = new FormData(document.getElementById("form"));
    showSpinner();
    $.ajax({
        url: _url,
        data: data,
        contentType: false,
        processData: false,
        type: 'POST',
        success: function(result){
            if (result.success) {
                // alert('Translate successful.');
                var url = _urlDownload + `?title=${result.title}`

                window.open(url, '_blank');
                hideSpinner();
            } else if (result.error) {
                alert(result.error);
                hideSpinner();
            }
        }
    });
});

function showSpinner()
{
    $('#form-loading').removeClass("d-none").addClass("d-flex");
}

function hideSpinner()
{
    $('#form-loading').removeClass("d-flex").addClass("d-none");
}