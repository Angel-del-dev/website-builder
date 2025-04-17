const { Request } = await require("../components/Request.inc.js");

const login = async e => {
    e.preventDefault();

    const UserNode = document.getElementById('User');
    const PasswordNode = document.getElementById('Password');
    const ErrorNode = document.getElementById('error');

    const User = UserNode.value.trim();
    const Password = PasswordNode.value.trim();

    ErrorNode.classList.add('d-none');
    [UserNode, PasswordNode].forEach((input, _) => input.classList.remove('error'));
    
    if(User === '') UserNode.classList.add('error');
    if(Password === '') PasswordNode.classList.add('error');
    if(User === '' || Password === '') return;

    const { message } = await Request({
        url: `/${BACKOFFICE_PREFIX}`,
        method: 'POST',
        data: { User, Password }
    });

    if(message !== '') {
        ErrorNode.textContent = message;
        ErrorNode.classList.remove('d-none');
        return;
    }

    location.href = `/${BACKOFFICE_PREFIX}/home`;
};

document.getElementById('login-form').addEventListener('submit', login);
document.getElementById('Submit').addEventListener('click', login);