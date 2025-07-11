// Read the query parameter (e.g. day.html?days=3)
const params = new URLSearchParams(window.location.search);
const days = params.get('days') || '3'; // default to 3 if not provided

// Dummy package list (normally comes from backend or JSON)
const packages = [
  { name: "3-Day Hanoi Special", days: 3, price: 16999, popular: true },
  { name: "4-Day Hoi An Delight", days: 4, price: 18999, popular: true },
  { name: "3-Day Ha Long Express", days: 3, price: 14999, popular: false },
  { name: "5-Day Vietnam Combo", days: 5, price: 21999, popular: true }
];

// Set page heading dynamically
document.getElementById("page-heading").innerText = `${days}-Day Vietnam Tour Packages`;
document.getElementById("page-subheading").innerText = `Discover our best ${days}-day Vietnam experiences tailored for Indian travelers.`;

// Filter and sort
const sortFilter = document.getElementById("sortFilter");
const packageList = document.getElementById("packageList");

function renderPackages(sortedList) {
  packageList.innerHTML = ""; // Clear
  sortedList.forEach(pkg => {
    const card = `
      <div class="bg-white shadow-md rounded p-4">
        <h2 class="text-xl font-semibold text-dark">${pkg.name}</h2>
        <p class="text-sm text-gray-500">${pkg.days} Days</p>
        <p class="text-red-600 font-bold mt-2">₹${pkg.price.toLocaleString()}</p>
      </div>`;
    packageList.innerHTML += card;
  });
}

function applyFilters() {
  let filtered = packages.filter(pkg => pkg.days == days);

  const sortBy = sortFilter.value;
  if (sortBy === 'low') filtered.sort((a, b) => a.price - b.price);
  else if (sortBy === 'high') filtered.sort((a, b) => b.price - a.price);
  else filtered.sort((a, b) => b.popular - a.popular);

  renderPackages(filtered);
}

// Initial load
applyFilters();
sortFilter.addEventListener('change', applyFilters);

/*  */




fetch("./components/header.html")
  .then(res => res.text())
  .then(html => {
    document.getElementById('header').innerHTML = html;

    // Attach event listeners AFTER inserting the header
    const menuToggle = document.getElementById("menu-toggle");
    const mobileMenu = document.getElementById("mobile-menu");

    if (menuToggle && mobileMenu) {
      menuToggle.addEventListener("click", () => {
        mobileMenu.classList.toggle("hidden");
      });
    }

    const dropdownToggle = document.getElementById("mobile-dropdown-toggle");
    const dropdown = document.getElementById("mobile-dropdown");
    const icon = document.getElementById("mobile-dropdown-icon");

    if (dropdownToggle && dropdown && icon) {
      dropdownToggle.addEventListener("click", () => {
        dropdown.classList.toggle("hidden");
        icon.classList.toggle("rotate-180");
      });
    }
  });





  window.addEventListener("scroll", function () {
    const header = document.getElementById("main-header");
    const nav = document.getElementById("main-nav");

    if (window.scrollY > 10) {
      header.classList.remove("bg-transparent");
      header.classList.add("bg-white", "shadow-md");
      nav.classList.remove("text-white");
      nav.classList.add("text-gray-800");
    } else {
      header.classList.remove("bg-white", "shadow-md");
      header.classList.add("bg-transparent");
      nav.classList.remove("text-gray-800");
      nav.classList.add("text-white");
    }
  });



fetch("./components/header.html")
    .then(res => res.text())
    .then(html => {
      document.getElementById('header').innerHTML = html;
    });

  // Load Footer
  fetch("./components/footer.html")
    .then(res => res.text())
    .then(html => {
      document.getElementById('footer').innerHTML = html;
    });


/* SLider for citys */



  function toggleQuoteModal() {
    const modal = document.getElementById('quoteModal');
    modal.classList.toggle('hidden');

    // Reset modal to step 1
    document.getElementById('step1').classList.remove('hidden');
    document.getElementById('step2').classList.add('hidden');
    document.getElementById('successMsg').classList.add('hidden');
  }

  function clickOutsideToClose(e) {
    if (e.target.id === 'quoteModal') toggleQuoteModal();
  }

  function goToStep2() {
    const name = document.getElementById('name').value.trim();
    const email = document.getElementById('email').value.trim();
    const phone = document.getElementById('phone').value.trim();

    if (name && email && phone) {
      document.getElementById('step1').classList.add('hidden');
      document.getElementById('step2').classList.remove('hidden');
    } else {
      alert("Please fill in all fields.");
    }
  }

  function submitForm() {
    // Collect all values
    const data = {
      name: document.getElementById('name').value.trim(),
      email: document.getElementById('email').value.trim(),
      phone: document.getElementById('phone').value.trim(),
      destination: document.getElementById('destination').value.trim(),
      days: document.getElementById('days').value.trim(),
      style: document.getElementById('style').value.trim(),
      notes: document.getElementById('notes').value.trim()
    };

    // You can use fetch() to send this to your backend here

    // Show success message
    document.getElementById('step2').classList.add('hidden');
    document.getElementById('successMsg').classList.remove('hidden');

    // Auto-close after 3s
    setTimeout(() => toggleQuoteModal(), 3000);
  }
