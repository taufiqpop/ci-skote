$(() => {
    if (sessionStorage.getItem('menu_data') !== null && sessionStorage.getItem('base_url') === BASE_URL && sessionStorage.getItem('role_name') === ROLE_NAME) {
        let data = JSON.parse(sessionStorage.getItem('menu_data'));
        generate(data);
    } else {
        loadSidebar();
    }
})

const loadSidebar = () => {
    $.get(BASE_URL + 'dashboard/loadMenu').then((res) => {

        const { data } = res;

        generate(data);
        sessionStorage.setItem('menu_data', JSON.stringify(data));
        sessionStorage.setItem('base_url', BASE_URL);
        sessionStorage.setItem('role_name', ROLE_NAME);

    })
}

const generate = (data) => {
    let container = $('#side-menu');
    container.empty();

    for (const group of data) {
        let { name, parent } = group;
        if (parent.length > 0) {
            container.append(`<li class="menu-title" key="t-pages">${name}</li>`);
            for (const menu of parent) {

                let { name, icon, route, id, child } = menu;

                let lowerName = name.toLowerCase();

                if (child.length > 0) {
                    let label = $('<span>', {
                        key: 't-' + name,
                        text: capitalizeFirstLetter(name)
                    });

                    let menu_icon = $('<i>', {
                        class: icon
                    });

                    let a = $('<a>', {
                        href: "javascript: void(0);",
                        class: 'has-arrow waves-effect',
                        html: [menu_icon, label]
                    });

                    let sub = $('<ul>', {
                        class: 'sub-menu',
                        'aria-expanded': false,
                    });

                    for (const item of child) {
                        let { name, route, id } = item;
                        let li = $('<li>', {
                            class: MENU_ACTIVE == name ? 'mm-active' : '',
                            html: $('<a>', {
                                href: BASE_URL + route + '/' + id,
                                text: capitalizeFirstLetter(name)
                            })
                        })

                        sub.append(li.prop('outerHTML'));
                    }

                    container.append($('<li>', {
                        class: MENU_OPEN == name ? 'mm-active' : '',
                        html: [a, sub],
                    }).prop('outerHTML'));
                } else {
                    let label = $('<span>', {
                        text: capitalizeFirstLetter(name),
                        key: 't-' + name,
                    });

                    let menu_icon = $('<i>', {
                        class: icon
                    });

                    let a = $('<a>', {
                        href: BASE_URL + route + '/' + id,
                        html: [menu_icon, label]
                    });

                    container.append($('<li>', {
                        class: MENU_ACTIVE == name ? 'mm-active' : '',
                        html: [a],
                    }).prop('outerHTML'));
                }
            }
        }

    }

    $('#side-menu').metisMenu('dispose');
    $('#side-menu').metisMenu();
}