const { Confirm } = await require("../components/alerts.inc.js");

const { Request } = await require("../components/Request.inc.js");
const { useTranslation } = await require("../components/hooks.inc.js");

const [ getTranslations ] = await useTranslation('backoffice');

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

const create_process = (text = '') => {
    const status_lbl = document.createElement('label');
    status_lbl.textContent = getTranslations('initial-status-not-started');
    status_lbl.style.setProperty('color', 'var(--red)');

    const lbl = document.createElement('label');
    lbl.classList.add('w-100', 'flex', 'justify-between', 'align-start', 'gap-2');
    lbl.append(document.createTextNode(`${text}:`), status_lbl);
    progress_node.append(lbl);
    return status_lbl;
};

const update_database = async () => {
    await Request({
        url: `/${BACKOFFICE_PREFIX}/initial/update-database`,
        method: 'POST'
    });
};

const change_prefix = async ({ Prefix }) => {
    await Request({
        url: `/${BACKOFFICE_PREFIX}/initial/change-prefix`,
        method: 'POST',
        data: { Prefix }
    });
};

const create_user = async ({ User, Password }) => {
    await Request({
        url: `/${BACKOFFICE_PREFIX}/initial/create-user`,
        method: 'POST',
        data: { User, Password }
    });
};

const block_user = async () => {
    await Request({
        url: `/${BACKOFFICE_PREFIX}/initial/block-user`,
        method: 'POST'
    });
};

const unset_initial = async () => {
    await Request({
        url: `/${BACKOFFICE_PREFIX}/initial/unset-initial`,
        method: 'POST'
    });
};

const sign_out = async () => {
    await Request({
        url: `/${BACKOFFICE_PREFIX}/initial/sign-out`,
        method: 'POST'
    });
};

const redirect = async ({ Prefix }, lbl) => {
    let count = 6;
    let interval = setInterval(() => {
        lbl.textContent = `${getTranslations('backoffice-redirecting-in')} ${--count}s`;
        if(count > 0) return; 
        clearInterval(interval);
        location.href = `/${Prefix}`;
    }, 1000);
};

const update_status = (lbl) => {
    lbl.textContent = getTranslations('initial-status-finished');
    lbl.style.setProperty('color', 'var(--green)');
};

const confirm_process = async ({ Prefix, User, Password }) => {
    progress_node.classList.remove('d-none');

    const btn = document.getElementById('Confirm');

    btn.classList.add('d-none');
   
    const process_list = [
        [create_process(getTranslations('initial-update-database')), update_database],
        [create_process(`${getTranslations('initial-create-user')} '${User}'`), create_user],
        [create_process(`${getTranslations('initial-block-user')} 'Initial'`), block_user],
        [create_process(getTranslations('initial-sign-out')), sign_out],
        [create_process(`${getTranslations('initial-unset_initial')}`), unset_initial],
        [create_process(getTranslations('initial-change-prefix')), change_prefix],
        [create_process(getTranslations('initial-redirect')), redirect]
    ];

    for(let [ lbl, callback ] of process_list) {
        await callback({ Prefix, User, Password }, lbl);
        update_status(lbl);
    }
};

const ask_confirm = async e => {
    e.preventDefault();
    progress_node.classList.add('d-none');
    progress_node.innerHTML = '';

    const Prefix_node = document.getElementById('panel-prefix');
    const User_node = document.getElementById('AdminUser');
    const Password_node = document.getElementById('AdminPassword');
    const ConfirmPassword_node = document.getElementById('ConfirmPassword');
    
    let error = false;

    [Prefix_node, User_node, Password_node, ConfirmPassword_node].forEach((input, _) => {
        if(input.value.trim() === '') {
            input.classList.add('error');
            error = true;
        }else input.classList.remove('error')
    });
    
    if(
        Password_node.value.trim() !== ConfirmPassword_node.value.trim()
    ) {
        [Password_node, ConfirmPassword_node].forEach((input, _) => input.classList.add('error'));
        error = true;
    }

    if(error) return;

    Confirm({
        title: getTranslations('backoffice-confirm'),
        text: getTranslations('initial-ask-confirm'),
        confirm_text: getTranslations('backoffice-confirm'),
        cancel_text: getTranslations('backoffice-cancel'),
        onConfirm: () => confirm_process({ 
            User: User_node.value.trim(),
            Password: Password_node.value.trim(),
            Prefix: Prefix_node.value.trim()
        })
    });
};
const progress_node = document.getElementById('progress');
document.getElementById('root').addEventListener('contextmenu', e => e.preventDefault());
document.getElementById('Language').addEventListener('change', change_process_language);
document.getElementById('Confirm').addEventListener('click', ask_confirm);