/**
 * useState
 *
 * @var initial_value
 */
export const useState = initial_value => {
    let value = initial_value;

    return [
        () => value,
        new_value => { value = new_value }
    ];
};


 /**
  * useTranslation
  *
  * @var [string] Location
  * @var [string] Specific_lang
  */
export const useTranslation = async (Location = '', Specific_lang = '') => {
    if(Location === '') throw new Error('Unespecified location');

    const { Request } = await require("./Request.inc.js");
    const { Translations, Lang } = await Request({
        url: `/${BACKOFFICE_PREFIX}/get-translation`,
        method: 'POST',
        data: { Location, Specific_lang }
    });

    return [
        key => {
            if(key === undefined) throw new Error('Key not specified');
            return Translations[key][Lang] || ''
        },
        () => Lang
    ];
};