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

// Start change language process
document.getElementById('main-language')?.addEventListener('change', async (el, _) => {
    const { Request } = await require("../components/Request.inc.js");

    await Request({
        url: `/${BACKOFFICE_PREFIX}/change-lang`,
        method: 'POST',
        data: { Lang: el.target.value }
    });

    location.reload();
});
// End change language process