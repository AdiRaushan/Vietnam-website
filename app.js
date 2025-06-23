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














/* For header and footer */

    async function loadComponent(id, file) {
      const res = await fetch(file);
      const html = await res.text();
      document.getElementById(id).innerHTML = html;
    }

    loadComponent("header", "./components/header.html");
    loadComponent("footer", "./components/footer.html");
