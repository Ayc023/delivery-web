// Cart logic
const cart = JSON.parse(localStorage.getItem('cart')) || [];

function addToCart(productId, productName, productPrice, productImage) {
  const existingProduct = cart.find((item) => item.id === productId);

  if (existingProduct) {
    existingProduct.quantity += 1;
  } else {
    cart.push({ id: productId, name: productName, price: productPrice, quantity: 1, image: productImage });
  }

  localStorage.setItem('cart', JSON.stringify(cart));
  alert(`${productName} has been added to the cart!`);
}

// Update cart dynamically
function updateCartDisplay() {
  const cartContainer = document.querySelector('.cart-items');
  if (!cartContainer) return;

  cartContainer.innerHTML = cart
    .map(
      (item) => `
    <div class="cart-item">
      <img src="${item.image}" alt="${item.name}" class="images">
      <div class="cart-item-details">
        <p class="cart-item-name">${item.name}</p>
        <p class="cart-item-price">₱${item.price.toFixed(2)}</p> <!-- Changed $ to ₱ and added .toFixed(2) for two decimal places -->
        <div class="cart-item-quantity">
          <button class="btn-quantity" onclick="updateQuantity(${item.id}, -1)">-</button>
          <input type="text" class="quantity-input" value="${item.quantity}" readonly>
          <button class="btn-quantity" onclick="updateQuantity(${item.id}, 1)">+</button>
        </div>
        <button class="btn-remove" onclick="removeFromCart(${item.id})">Remove</button>
      </div>
    </div>
  `
    )
    .join('');

  updateTotalPrice();
}

// Update product quantity
function updateQuantity(productId, change) {
  const product = cart.find(item => item.id === productId);
  if (product) {
    product.quantity += change;
    if (product.quantity <= 0) product.quantity = 1;  // Ensure quantity doesn't go below 1
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartDisplay();
  }
}

// Remove product from cart
function removeFromCart(productId) {
  const updatedCart = cart.filter((item) => item.id !== productId);
  localStorage.setItem('cart', JSON.stringify(updatedCart));
  updateCartDisplay();
}

// Calculate and display the total price
function updateTotalPrice() {
  const totalPrice = cart.reduce((acc, item) => acc + item.price * item.quantity, 0);
  const totalElement = document.querySelector('.total-price');
  if (totalElement) {
    totalElement.textContent = `₱${totalPrice.toFixed(2)}`; // Updated to show peso sign and fixed to 2 decimal places
  }
}

// Smooth scrolling
document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
  anchor.addEventListener('click', function (e) {
    e.preventDefault();
    document.querySelector(this.getAttribute('href')).scrollIntoView({ behavior: 'smooth' });
  });
});

// Initial cart update
document.addEventListener('DOMContentLoaded', updateCartDisplay);
