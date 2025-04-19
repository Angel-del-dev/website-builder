const require = async location => {
    return await import(`${location}?v${new Date().getTime()}`);
}