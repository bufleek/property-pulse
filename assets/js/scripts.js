function toggleFilters() {
  const filters = document.querySelector(".hidden-filters");
  if (filters.style.display === "none" || filters.style.display === "") {
    filters.style.display = "grid";
  } else {
    filters.style.display = "none";
  }
}
