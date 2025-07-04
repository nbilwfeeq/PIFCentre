<style>
    .collection-container {
    position: relative;
    width: 80%;
    margin: auto;
    overflow: hidden;
    }

    .collection-books {
    display: flex;
    transition: transform 0.5s ease-in-out;
    }

    .collection-book {
    width: 25%; /* Show 4 books at once */
    padding: 20px;
    text-align: center;
    background-color: lightgrey;
    margin: 0 10px;
    border-radius: 5px;
    }

    .collection-book img {
    width: 245px;
    }

    .collection-prev, .collection-next {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background-color: rgba(0, 0, 0, 0.5);
    color: white;
    border: none;
    padding: 10px;
    font-size: 20px;
    cursor: pointer;
    z-index: 10;
    }

    .collection-prev {
    left: 10px;
    }

    .collection-next {
    right: 10px;
    }
</style>

<br><br>
<h1 class="catalog-title text-center" data-aos="fade-up">Koleksi Buku Baharu</h1>
<br><br>

<div class="collection-container">
  <div class="collection-books">
    <div class="collection-book"><img src="images/books/1d0bcb640e938ce08ef1a6d602e8af4f.jpg" alt="Book 1"></div>
    <div class="collection-book"><img src="images/books/055e40194f06a022dd2ac62b17eadd04.jpg" alt="Book 2"></div>
    <div class="collection-book"><img src="images/books/f81ca762bf8665df37a127062a5aa143.png" alt="Book 3"></div>
    <div class="collection-book"><img src="images/books/9780135261781.jpg" alt="Book 4"></div>
    <div class="collection-book"><img src="images/books/default.jpg" alt="Book 5"></div>
    <div class="collection-book"><img src="images/books/9781266090608-Belch.jpg" alt="Book 6"></div>
    <div class="collection-book"><img src="images/books/91PIBxoE4vL._AC_UF894,1000_QL80_.jpg" alt="Book 7"></div>
  </div>
  <button class="collection-prev">&#10094;</button>
  <button class="collection-next">&#10095;</button>
</div>

<script>
    let currentIndex = 0;
    const books = document.querySelector('.collection-books');
    const totalBooks = document.querySelectorAll('.collection-book').length;
    const visibleBooks = 4; // Display 4 books at a time

    const prevButton = document.querySelector('.collection-prev');
    const nextButton = document.querySelector('.collection-next');

    function moveToNext() {
    if (currentIndex < totalBooks - visibleBooks) {
        currentIndex++;
    } else {
        currentIndex = 0; // loop back to the first set
    }
    updateCollectionPosition();
    }

    function moveToPrev() {
    if (currentIndex > 0) {
        currentIndex--;
    } else {
        currentIndex = totalBooks - visibleBooks; // loop back to the last set
    }
    updateCollectionPosition();
    }

    function updateCollectionPosition() {
    books.style.transform = `translateX(-${currentIndex * (100 / visibleBooks)}%)`;
    }

    // Automatic sliding every 3 seconds
    setInterval(moveToNext, 3000);

    // Event listeners for buttons
    prevButton.addEventListener('click', moveToPrev);
    nextButton.addEventListener('click', moveToNext);


</script>