const { useTranslation }  = await require('../components/hooks.inc.js');
const { Confirm, Prompt, Alert }  = await require('../components/alerts.inc.js');
const { Request } = await require("../components/Request.inc.js");
const  [ getTranslation ] = await useTranslation('backoffice');

const confirm_remove_domain = async Domain => {
    const { message } = await Request({
        url: `/${BACKOFFICE_PREFIX}/diagnostics/domains/remove`,
        method: 'POST',
        data: { Domain }
    });
    if(message !== undefined) {
        return Alert({ 
            text: message,
            confirm_text: getTranslation('backoffice-confirm')
        });
    }

    location.reload();
};

const remove_domain = e => {    
    const DomainNode = e.target.closest('[domain]');
    if(DomainNode === null) return;
    const Domain = DomainNode.getAttribute('domain').trim();
    if(Domain === '') return;

    Confirm({
        title: getTranslation('backoffice-domain'),
        text: getTranslation('backoffice-remove-domain'),
        onConfirm: () => confirm_remove_domain(Domain)
    })
};

const confirm_create = async ([ _, domain ]) => {
    let Domain = domain.value.trim();
    if(Domain === '') return ask_create();

    const { message } = await Request({
        url: `/${BACKOFFICE_PREFIX}/diagnostics/domains/add`,
        method: 'POST',
        data: { Domain }
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
        title: getTranslation('backoffice-add-domain'),
        confirm_text: getTranslation('backoffice-confirm'),
        cancel_text: getTranslation('backoffice-cancel'),
        items: [
            { type: 'label', value: getTranslation('backoffice-domain') },
            { type: 'text', value: '', placeholder: 'https://acme.com' }
        ],
        onConfirm: items => confirm_create(items)
    });
}

const request_domain_validation = async e =>{
    const DomainNode = e.target.closest('[domain]');
    if(DomainNode === null) return;
    const Domain = DomainNode.getAttribute('domain');

    const { message, Verificator } = await Request({
        url: `/${BACKOFFICE_PREFIX}/diagnostics/domains/get-validation`,
        method: 'POST',
        data: { Domain }
    });
    if(message !== undefined) {
        return Alert({ 
            text: message,
            confirm_text: getTranslation('backoffice-confirm')
        });
    }
    Confirm({
        title: getTranslation('backoffice-validate-domain-title'),
        text: `${getTranslation('backoffice-validate-domain').replace('%s', `'${Domain}/tools.d'`)}<br /><textarea disabled style='height: 20vmin;border: 1px solid lightgray; padding: 5px; border-radius: 5px;width: 100%;resize: vertical;color: var(--red)'>${Verificator}</textarea>`,
        onConfirm: () => null
    })
}

document.querySelector('article button.add-domain').addEventListener('click', e => {
    e.preventDefault();
    ask_create();
});

document.querySelectorAll('.delete')
    ?.forEach((node, _) => node.addEventListener('click', remove_domain));

document.querySelectorAll('.validate_domain')
    ?.forEach((node, _) => node.addEventListener('click', request_domain_validation));