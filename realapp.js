// === GLOBAL COMPONENT LOADER ===
document.addEventListener("DOMContentLoaded", () => {
  const loadComponent = async (id, file) => {
    const res = await fetch(file);
    const html = await res.text();
    const el = document.getElementById(id);
    if (el) el.innerHTML = html;
  };

  const initHeader = () => {
    const menuToggle = document.getElementById("mobile-menu-toggle");
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
  };

  // Load shared components
  loadComponent("header", "./components/header.html", initHeader);
  loadComponent("footer", "./components/footer.html");
  loadComponent("banner", "./components/banner.html");

  const toggleSidebar = () => {
    const sidebar = document.getElementById("filterSidebar");
    if (sidebar) sidebar.classList.toggle("translate-x-full");
  };
  window.toggleSidebar = toggleSidebar;

  // === INDEX PAGE ===
  if (location.pathname.includes("index.html")) {
    const exploreBtn = document.getElementById("explore-btn");
    if (exploreBtn) {
      exploreBtn.addEventListener("click", () => {
        const city = document.getElementById("filter-city").value;
        const style = document.getElementById("filter-style").value;
        const duration = document.getElementById("filter-duration").value;

        const params = new URLSearchParams();
        if (city && city !== "Destination") params.append("city", city);
        if (style && style !== "Tour Style") params.append("style", style);
        if (duration && duration !== "Tour Duration") {
          const daysValue = duration.includes("1") ? 1 : duration.includes("3") ? 3 : 5;
          params.append("days", daysValue);
        }
        window.location.href = `days-page.html?${params.toString()}`;
      });
    }
  }

  // === ABOUT PAGE SLIDER ===
  if (location.pathname.includes("About.html")) {
    // No specific JS needed; slider is CSS-based
  }

  // === FILTER PAGES (cities.html, days-page.html, style.html) ===
  if (
    location.pathname.includes("cities.html") ||
    location.pathname.includes("days-page.html") ||
    location.pathname.includes("style.html")
  ) {
    let tours = [];

    const getUnique = (arr, key) => [...new Set(arr.map(item => item[key]))];

    const renderCheckboxes = (list, id, type) => {
      const params = new URLSearchParams(window.location.search);
      const typeParam = params.get("type");
      const valueParam = params.get("value");
      const container = document.getElementById(id);
      if (!container) return;

      container.innerHTML = list.map(item => {
        const label = type === "days" ? `${item} Days` : item;
        const value = type === "days" ? Number(item) : item;
        const isChecked =
          type === typeParam && value.toString().toLowerCase() === valueParam?.toLowerCase()
            ? "checked"
            : "";
        return `
          <label class="flex items-center gap-2">
            <input type="checkbox" value="${value}" data-type="${type}" class="filter-checkbox" ${isChecked}> ${label}
          </label>`;
      }).join("");
    };

    const populateFilters = () => {
      renderCheckboxes(getUnique(tours, "city"), "filterCities", "city");
      renderCheckboxes(getUnique(tours, "days"), "filterDays", "days");
      renderCheckboxes(getUnique(tours, "style"), "filterStyles", "style");
    };

    const updatePageTitle = () => {
      const params = new URLSearchParams(window.location.search);
      const type = params.get("type");
      const value = params.get("value");
      const title = document.getElementById("pageTitle");
      if (type && value && title) {
        title.textContent = `Showing ${value} ${type === "style" ? "Tours" : "Packages"}`;
      }
    };

    const applyURLFilters = () => {
      const params = new URLSearchParams(window.location.search);
      const filters = {
        days: params.get("days"),
        city: params.get("city"),
        style: params.get("style"),
        type: params.get("type"),
        value: params.get("value")
      };

      document.querySelectorAll(".filter-checkbox").forEach(cb => {
        const type = cb.dataset.type;
        const val = cb.value;
        if (filters[type] && val.toLowerCase() === filters[type].toString().toLowerCase()) {
          cb.checked = true;
        }
      });

      renderTours();
    };

    const renderTours = () => {
      const selectedFilters = { city: [], days: [], style: [] };

      document.querySelectorAll(".filter-checkbox:checked").forEach(cb => {
        const type = cb.dataset.type;
        const value = type === "days" ? Number(cb.value) : cb.value;
        selectedFilters[type].push(value);
      });

      let result = tours.filter(t =>
        (!selectedFilters.city.length || selectedFilters.city.includes(t.city)) &&
        (!selectedFilters.days.length || selectedFilters.days.includes(Number(t.days))) &&
        (!selectedFilters.style.length || selectedFilters.style.includes(t.style))
      );

      const sort = document.getElementById("sortSelect")?.value;
      if (sort === "priceLowHigh") result.sort((a, b) => a.price - b.price);
      if (sort === "priceHighLow") result.sort((a, b) => b.price - a.price);
      if (sort === "ratingHigh") result.sort((a, b) => b.rating - a.rating);

      const container = document.getElementById("packageContainer");
      if (!container) return;
      container.innerHTML = result.map(t => `
        <div class="bg-white p-4 rounded shadow">
          <img src="${t.image}" class="rounded mb-2 h-40 w-full object-cover" alt="${t.title}" />
          <h3 class="font-bold text-lg">${t.title}</h3>
          <p class="text-sm text-gray-500">${t.days} Days - ₹${t.price}</p>
        </div>
      `).join("");
    };

    fetch("./data/data.json")
      .then(res => res.json())
      .then(data => {
        tours = data;
        populateFilters();
        applyURLFilters();
        updatePageTitle();
      });
  }
});
