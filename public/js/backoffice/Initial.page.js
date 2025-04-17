const { Request } = await require("../components/Request.inc.js");

const change_process_language = async e => {
    e.preventDefault();
    const Lang = e.target.value;
    if(Lang.trim() === '') return;
    await Request({
        url: `/${BACKOFFICE_PREFIX}/change-lang`,
        method: 'POST',
        data: { Lang }
    });

    location.reload();
};

document.getElementById('Language').addEventListener('change', change_process_language);