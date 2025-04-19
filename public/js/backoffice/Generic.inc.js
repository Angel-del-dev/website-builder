// Start menu related process
document.querySelectorAll('aside .menu-section').forEach((section, _) => {
    section.addEventListener('click', e => {
        e.preventDefault();
        const classList = section.closest('ul').querySelector('ul').classList;
        if(classList.contains('d-none')) classList.remove('d-none');
        else classList.add('d-none');
    });
});
// End menu related process