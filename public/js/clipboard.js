(() => {
  const copyButtons = document.querySelectorAll('[data-xt-action="clipboard"]');

  if (copyButtons instanceof NodeList) {
    copyButtons.forEach((button) => {
      button.addEventListener("click", (e) => {
        const target =
          e.target.tagName === "I" ? e.target.parentNode : e.target;
        const contentToCopy = document.querySelector(target.dataset.xtTarget);
        // Select the text field
        contentToCopy.select();
        contentToCopy.setSelectionRange(0, 99999); // For mobile devices

        // Copy the text inside the text field
        document.execCommand("copy");
      });
    });
  }
})();
