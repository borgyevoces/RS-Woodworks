const links = document.querySelectorAll('.sidebar ul li a');

links.forEach(link => {
  link.addEventListener('click', () => {
    links.forEach(item => {
      item.classList.remove('active');
    });
    link.classList.add('active');
  });
});


function selectColor(color) {
  document.getElementById('furniture-image').src = `images/${color}_furniture.png`;
}

function selectDesign(design) {
  document.getElementById('furniture-image').src = `images/${design}_red_furniture.png`;
}





