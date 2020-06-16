
// Listen for click on the document
document.addEventListener('click', function (event) {

  //Bail if our clicked element doesn't have the class
  if (!event.target.classList.contains('accordeon__item--title')) return;

  // Get the target content
  //var content = document.querySelector(event.target.attributes.getNamedItem('data-target').value);

  // Return early if there is no target
  //if (!content) return;

  // Prevent default link behavior
  //event.preventDefault();

  // If the content is already expanded, collapse it and quit
  if (event.target.parentElement.classList.contains('open')) {
    event.target.parentElement.classList.remove('open');
    return;
  }

  // Get all open accordion content, loop through it, and close it
  var accordions = document.querySelectorAll('.accordeon__item.open');
  for (var i = 0; i < accordions.length; i++) {
    accordions[i].classList.remove('open');
  }

  // Toggle our content
  event.target.parentElement.classList.toggle('open');
});