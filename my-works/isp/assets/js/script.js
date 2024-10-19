$(function(){
	$('.bookAppointmentButton').tooltip({
		title: "Book An Appointment",
		placement: "top"
	});

	$('[data-bs-toggle="tooltip"]').tooltip();
})

function previewImage(event){
	const input = event.target;
	const reader = new FileReader();

	reader.onload = function(){
		const img = document.getElementById('imagePreview');
		img.src = reader.result;
		img.style.display = 'block';
	}

	if(input.files && input.files[0]){
		reader.readAsDataURL(input.files[0]);
	}
}