$(function () {
    const addButton = $('[data-type=add_button]');
    let buttonCount = $('.buttons_block').children().length;

    class ButtonHtml {
        constructor(count = 0) {
            this.count = count;
            this.dom = null;
            this.destroy_button = null;
            this.buildDom();
        }

        buildDom() {
            this.dom = document.createElement('div');
            const button_title = document.createElement('input');
            $(button_title)
                .attr('type', 'text')
                .attr('placeholder', 'Button Title')
                .attr('required', 'required')
                .attr('name', `buttons_title[${this.count}]`)
                .addClass('form-control m');
            const title_label = document.createElement('label');
            $(title_label)
                .addClass('col-lg-3 col-xs-12 required')
                .attr('for', `buttons_title[${this.count}]`)
                .text('Title');
            const title_dom = document.createElement('div');
            $(title_dom)
                .addClass('col-md-5')
                .append(title_label, button_title);

            const button_link = document.createElement('input');
            $(button_link)
                .attr('type', 'text')
                .attr('required', 'required')
                .attr('placeholder', 'Button Link')
                .attr('name', `buttons_link[${this.count}]`)
                .addClass('form-control m');
            const link_label = document.createElement('label');
            $(link_label)
                .addClass('col-lg-3 col-xs-12 required')
                .attr('for', `buttons_title[${this.count}]`)
                .text('Link');
            const link_dom = document.createElement('div');
            $(link_dom)
                .addClass('col-md-5')
                .append(link_label, button_link);

            this.destroy_button = document.createElement('button');
            $(this.destroy_button)
                .attr('type', 'button')
                .addClass('btn btn-danger m')
                .attr('data-predestination', 'delete_button')
                .attr('data-id', this.count)
                .text('delete');
            const destroy_dom = document.createElement('div');
            $(destroy_dom)
                .addClass('col-md-1')
                .append(this.destroy_button);

            $(this.dom)
                .addClass('row')
                .attr('data-dom-id', this.count)
                .append(title_dom, link_dom, destroy_dom);

            $(this.destroy_button).on('click', () => {
                this.destroySelf()
            })
        }

        destroySelf() {
            $(this.dom).remove();
            delete this;
        }

        getDom() {
            return this.dom;
        }
    }

    $(addButton).on('click', function (e) {
        ++buttonCount;
        const parent = e.target.offsetParent;
        $(parent).find('.buttons_block').append(new ButtonHtml(buttonCount).getDom());
    });

    $('#desktop_image').change(function (e) {
        if ($(this).val() !== '') {
            preview_image(e, 'desktop');
        }
    });

    $('#mobile_image').change(function (e) {
        if ($(this).val() !== '') {
            preview_image(e, 'mobile');
        }
    });

    function preview_image(event, id) {
        const reader = new FileReader();
        reader.onload = function () {
            const output = document.getElementById(`${id}_img_container`);
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }

    $(document).on('click', '[data-predestination=delete_button]', function () {
        $('.buttons_block').find(`[data-dom-id=${$(this).attr('data-id')}]`).remove();
    });

    $(document).on('submit', '#headerCarouselForm', function () {
        $(this).find(':input[type=submit]')
            .attr('disabled', true)
            .removeClass('btn-success')
            .addClass('btn-danger');
    })
});