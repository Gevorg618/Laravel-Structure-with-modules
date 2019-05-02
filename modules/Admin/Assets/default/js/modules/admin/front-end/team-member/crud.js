$(function () {
    const addSocialLink = $('[data-type=add_socialLink]');
    let socialLinkCount = $('.social_link_block').children().length;

    class SocialLinkHtml {
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
                .attr('placeholder', 'Social Icon')
                .attr('required', 'required')
                .attr('name', `social_icon[${this.count}]`)
                .addClass('form-control m');
            const title_label = document.createElement('label');
            $(title_label)
                .addClass('col-lg-3 col-xs-12 required')
                .attr('for', `social_icon[${this.count}]`)
                .text('Icon');
            const title_dom = document.createElement('div');
            $(title_dom)
                .addClass('col-md-5')
                .append(title_label, button_title);

            const button_link = document.createElement('input');
            $(button_link)
                .attr('type', 'text')
                .attr('placeholder', 'Social Url')
                .attr('required', 'required')
                .attr('name', `social_url[${this.count}]`)
                .addClass('form-control m');
            const link_label = document.createElement('label');
            $(link_label)
                .addClass('col-lg-3 col-xs-12 required')
                .attr('for', `social_url[${this.count}]`)
                .text('Url');
            const link_dom = document.createElement('div');
            $(link_dom)
                .addClass('col-md-5')
                .append(link_label, button_link);

            this.destroy_button = document.createElement('button');
            $(this.destroy_button)
                .attr('type', 'button')
                .addClass('btn btn-danger m')
                .attr('data-predestination', 'delete_social_link')
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

    $(addSocialLink).on('click', function (e) {
        ++socialLinkCount;
        const parent = e.target.offsetParent;
        $(parent).find('.social_link_block').append(new SocialLinkHtml(socialLinkCount).getDom());
    });

    $('#image').change(function (e) {
        if ($(this).val() !== '') {
            preview_image(e, 'image');
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

    $(document).on('click', '[data-predestination=delete_social_link]', function () {
        $('.social_link_block').find(`[data-dom-id=${$(this).attr('data-id')}]`).remove();
    });

    $(document).on('submit', '#teamMemberForm', function () {
        $(this).find(':input[type=submit]')
            .attr('disabled', true)
            .removeClass('btn-success')
            .addClass('btn-danger');
    })

});