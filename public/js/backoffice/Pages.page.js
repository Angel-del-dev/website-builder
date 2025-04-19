const { useTranslation }  = await require('../components/hooks.inc.js');
const { Confirm, Prompt, Alert }  = await require('../components/alerts.inc.js');

const { Request } = await require("../components/Request.inc.js");

const  [ getTranslation ] = await useTranslation('backoffice');

const confirm_delete = async Slug => {
    await Request({
        url: `/${BACKOFFICE_PREFIX}/pages/page/delete`,
        method: 'POST',
        data: { Slug }
    });

    document.querySelector(`article [page="${Slug}"]`)?.remove();
};

const confirm_create = async ([ _, route ]) => {
    let Slug = route.value.trim();
    if(Slug === '') return ask_create();

    if(Slug[0] !== '/') Slug = `/${Slug}`;

    const { message } = await Request({
        url: `/${BACKOFFICE_PREFIX}/pages/page/insert`,
        method: 'POST',
        data: { Slug: Slug }
    });
    if(message !== undefined) {
        return Alert({ 
            text: message,
            confirm_text: getTranslation('backoffice-confirm')
        });
    }

    location.reload();
};

const ask_create = () => {
    Prompt({
        title: getTranslation('backoffice-confirm'),
        confirm_text: getTranslation('backoffice-confirm'),
        cancel_text: getTranslation('backoffice-cancel'),
        items: [
            { type: 'label', value: getTranslation('backoffice-page') },
            { type: 'text', value: '', placeholder: '/' }
        ],
        onConfirm: items => confirm_create(items)
    });
}

const ask_delete = (e, ref) => {
    e.preventDefault();
    Confirm({
        title: getTranslation('backoffice-confirm'),
        text: getTranslation('backoffice-confirm-remove'),
        confirm_text: getTranslation('backoffice-confirm'),
        cancel_text: getTranslation('backoffice-cancel'),
        onConfirm: () => confirm_delete(ref)
    })
};

document.querySelectorAll('article a.delete').forEach((item, _) => {
    item.addEventListener('click', e => ask_delete(e, item.closest('[page]').getAttribute('page')));
});

document.querySelector('article button.add-page').addEventListener('click', e => {
    e.preventDefault();
    ask_create();
});