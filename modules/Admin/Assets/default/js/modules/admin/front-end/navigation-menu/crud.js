$(function () {
    const NavigationCache = [];
    const checkbox = $('#is_drop_down');
    const childes_section = $('.childes_section');
    const addChildes = $('[data-type=add_child]');
    const childes_block = $('.childes_block');
    let count = $(childes_block).children().length;

    $(checkbox).on('change', function () {
        if (this.checked) {
            $(childes_section).show(100);
        } else {
            $(childes_section).hide(100);
        }
    });

    class ChildesHtml {
        constructor(count = 0) {
            this.count = count;
            this.dom = null;
            this.destroy_button = null;
            this.add_child = null;
            this.buildDom();
            NavigationCache.push(this)
        }

        buildDom() {
            this.dom = document.createElement('div');

            const child_title = document.createElement('input');
            $(child_title)
                .attr('type', 'text')
                .attr('required', 'required')
                .attr('placeholder', 'Child Title')
                .attr('name', `child_title[${this.count}]`)
                .addClass('form-control m');
            const title_label = document.createElement('label');
            $(title_label)
                .addClass('col-lg-6 col-xs-12 required')
                .css({
                    'margin': 0
                })
                .attr('for', `child_title[${this.count}]`)
                .text('Child Title');
            const title_dom = document.createElement('div');
            $(title_dom)
                .addClass('col-md-5')
                .append(title_label, child_title);

            const child_url = document.createElement('input');
            $(child_url)
                .attr('type', 'text')
                .attr('required', 'required')
                .attr('placeholder', 'Child Url')
                .attr('name', `child_url[${this.count}]`)
                .addClass('form-control m');
            const url_label = document.createElement('label');
            $(url_label)
                .addClass('col-lg-6 col-xs-12 required')
                .css({
                    'margin': 0
                })
                .attr('for', `child_url[${this.count}]`)
                .text('Child Url');
            const url_dom = document.createElement('div');
            $(url_dom)
                .addClass('col-md-5')
                .append(url_label, child_url);

            this.destroy_button = document.createElement('button');
            $(this.destroy_button)
                .attr('type', 'button')
                .addClass('btn btn-danger m')
                .attr('data-predestination', 'delete_child')
                .attr('data-id', this.count)
                .text('Delete');
            const destroy_dom = document.createElement('div');
            $(destroy_dom)
                .addClass('col-md-1')
                .append(this.destroy_button);

            $(this.dom)
                .addClass('row')
                .attr('data-dom-id', this.count)
                .append(title_dom, url_dom, destroy_dom);

            $(this.destroy_button).on('click', () => {
                this.destroySelf();
            })

        }

        getDom() {
            return this.dom;
        }

        destroySelf() {
            $(this.dom).remove();
            delete this;
        }
    }

    $(addChildes).on('click', function () {
        ++count;
        $(childes_block).append(new ChildesHtml(count).getDom())
    });

    $(document).on('click', '[data-predestination=delete_child]', function () {
        $('.childes_block').find(`[data-dom-id=${$(this).attr('data-id')}]`).remove();
    });

    $(document).one('submit', '#navigationMenuForm', function (e) {
        e.preventDefault();
        $(this).find(':input[type=submit]')
            .attr('disabled', true)
            .removeClass('btn-success')
            .addClass('btn-danger');

        // Check availability childes dom element
        if (!$(checkbox).is(':checked') && $(childes_block).children().length) {
            NavigationCache.map(item => {
                item.destroySelf();
            });
            $.each($(childes_block).children(), (index, value) => {
                $(value).remove();
            })
        }
        if( $(checkbox).is(':checked') && !$(childes_block).children().length) {
            $(checkbox).removeAttr('checked')
        }

        //Submit
        $(this).submit();
    });

});