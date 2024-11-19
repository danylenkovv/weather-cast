document.addEventListener("DOMContentLoaded", () => {
    // Carousel functionality
    const itemsContainer = document.querySelector(".weather-items");
    const items = Array.from(itemsContainer.querySelectorAll(".item"));
    const visibleCount = 7;
    let startIndex = items.findIndex(item => item.classList.contains("active"));

    function updateCarousel() {
        if (startIndex + visibleCount > items.length + 1) {
            startIndex = Math.max(0, items.length +1 - visibleCount);
        }

        items.forEach((item, index) => {
            item.style.display = (index >= startIndex && index < startIndex + visibleCount) ? "block" : "none";
        });
    }

    document.getElementById("next").addEventListener("click", () => {
        if (startIndex + visibleCount < items.length + 1) {
            startIndex++;
            updateCarousel();
        }
    });

    document.getElementById("prev").addEventListener("click", () => {
        if (startIndex > 0) {
            startIndex--;
            updateCarousel();
        }
    });

    //Add active class to links
    function setActiveLink() {
        const currentAction = new URLSearchParams(window.location.search).get("action");
        const links = document.querySelectorAll(".links a");

        links.forEach(link => {
            const linkAction = new URL(link.href).searchParams.get("action");
            if (linkAction === currentAction) {
                link.classList.add("active");
            } else {
                link.classList.remove("active");
            }
        });
    }

    setActiveLink();
    updateCarousel();
});

// Modal functionality
(function() {
  const modalCalendar = document.getElementById("modalCalendar");
  const calendarIcon = document.querySelector(".fa-calendar-days");
  const closeBtnCalendar = modalCalendar.querySelector(".close-btn");

  const modalSearch = document.getElementById("modalSearch");
  const searchIcon = document.querySelector(".search");
  const closeBtnSearch = modalSearch.querySelector(".close-btn");

  const toggleModal = (modalWindow, display) => {
    modalWindow.style.display = display;
  };

  calendarIcon.addEventListener("click", () => toggleModal(modalCalendar, "block"));
  closeBtnCalendar.addEventListener("click", () => toggleModal(modalCalendar, "none"));

  searchIcon.addEventListener("click", () => toggleModal(modalSearch, "block"));
  closeBtnSearch.addEventListener("click", () => toggleModal(modalSearch, "none"));

  window.addEventListener("click", (e) => {
    if (e.target === modalCalendar || e.target === modalSearch) {
      toggleModal(modalCalendar, "none");
      toggleModal(modalSearch, "none");
    }
  });
})();

// Date picker logic
function createDatePicker() {
  const datepicker = document.getElementById("datepicker");
  const currentDate = new Date();
  
  const startDate = new Date(currentDate.getTime());
  startDate.setDate(startDate.getDate() - 365);

  const endDate = new Date(currentDate.getTime());
  endDate.setDate(endDate.getDate() + 300);
  const monthNames = [
    "January", "February", "March", "April", "May", "June", "July", "August",
    "September", "October", "November", "December"
  ];

  let [currentMonth, currentYear] = [currentDate.getMonth(), currentDate.getFullYear()];

  const calendarTable = document.createElement("table");
  datepicker.appendChild(calendarTable);

  const renderCalendar = (month, year) => {
    calendarTable.innerHTML = "";
    createHeaderRow(month, year);
    createDaysOfWeekRow();
    createDateCells(month, year);
    updateNavButtons(month, year);
  };

  const createHeaderRow = (month, year) => {
    const headerRow = document.createElement("tr");
    const headerCell = document.createElement("th");
    headerCell.colSpan = 7;
    headerCell.textContent = `${monthNames[month]} ${year}`;
    headerRow.appendChild(headerCell);
    calendarTable.appendChild(headerRow);
  };

  const createDaysOfWeekRow = () => {
    const daysOfWeekRow = document.createElement("tr");
    ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"].forEach(day => {
      const th = document.createElement("th");
      th.textContent = day;
      daysOfWeekRow.appendChild(th);
    });
    calendarTable.appendChild(daysOfWeekRow);
  };

  const createDateCells = (month, year) => {
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    const firstDay = new Date(year, month, 1).getDay();
    const prevMonthDays = new Date(year, month, 0).getDate();
    
    let date = 1, nextMonthDate = 1;

    for (let i = 0; i < 6; i++) {
      const row = document.createElement("tr");
      for (let j = 0; j < 7; j++) {
        const cell = document.createElement("td");
        if (i === 0 && j < firstDay) {
          fillDate(cell, prevMonthDays - firstDay + j + 1, "inactive-date");
        } else if (date > daysInMonth) {
          fillDate(cell, nextMonthDate++, "inactive-date");
        } else {
          const cellDate = new Date(year, month, date);
          const isValid = cellDate >= startDate && cellDate <= endDate;
          fillDate(cell, date++, isValid ? "" : "inactive-date", isValid ? () => handleActiveDateSelection(cellDate) : null);
        }
        row.appendChild(cell);
      }
      calendarTable.appendChild(row);
    }
  };

  const fillDate = (cell, date, className, clickHandler = null) => {
    cell.textContent = date;
    if (className) cell.classList.add(className);
    if (clickHandler) cell.addEventListener("click", () => {
      document.querySelectorAll("#datepicker td").forEach(td => td.classList.remove("active"));
      cell.classList.add("active");
      clickHandler();
    });
  };

  const handleActiveDateSelection = (selectedDate) => {
  selectedDate.setHours(12, 0, 0, 0);
  
  const formattedDate = selectedDate.toISOString().split('T')[0];
  window.location.href = `index.php?action=specific_day&date=${formattedDate}`;
  };


  const updateNavButtons = (month, year) => {
    const prevButton = document.getElementById("prevMonth");
    const nextButton = document.getElementById("nextMonth");

    prevButton.disabled = new Date(year, month, 1) <= startDate;
    nextButton.disabled = new Date(year, month + 1, 0) >= endDate;

    prevButton.classList.toggle("inactive-nav", prevButton.disabled);
    nextButton.classList.toggle("inactive-nav", nextButton.disabled);
  };

  document.getElementById("prevMonth").addEventListener("click", () => {
    [currentMonth, currentYear] = adjustMonthYear(currentMonth - 1, currentYear);
    renderCalendar(currentMonth, currentYear);
  });

  document.getElementById("nextMonth").addEventListener("click", () => {
    [currentMonth, currentYear] = adjustMonthYear(currentMonth + 1, currentYear);
    renderCalendar(currentMonth, currentYear);
  });

  const adjustMonthYear = (month, year) => {
    if (month < 0) return [11, year - 1];
    if (month > 11) return [0, year + 1];
    return [month, year];
  };

  renderCalendar(currentMonth, currentYear);
}

createDatePicker();
