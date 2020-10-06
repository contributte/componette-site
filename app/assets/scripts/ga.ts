const init = (): void => {
  const links = document.querySelectorAll('a[data-ga]');
  for (const link of links) {
    link.addEventListener('click', () => {
      ga(
        'send',
        link.getAttribute('data-ga-event'),
        link.getAttribute('data-ga-event'),
        link.getAttribute('data-ga-category'),
        link.getAttribute('data-ga-action')
      );
    });
  }
};

export default { init };
