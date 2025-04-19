const { useTranslation }  = await require('../components/hooks.inc.js');
const { Confirm }  = await require('../components/alerts.inc.js');

const { Request } = await require("../components/Request.inc.js");

const  [ getTranslation ] = await useTranslation('backoffice');

const confirm_delete = async Page => {
    await Request({
        url: `/${BACKOFFICE_PREFIX}/pages/page/delete`,
        method: 'POST',
        data: { Page }
    });

    document.querySelector(`article [page="${Page}"]`)?.remove();
};

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