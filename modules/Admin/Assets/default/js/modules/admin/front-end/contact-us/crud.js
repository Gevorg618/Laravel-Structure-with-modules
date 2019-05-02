$(function () {
    let addReceiver = $('[data-type=add_receiver]');
    let receiverCount = $('.receiver_block').children().length;

    class ReceiverHtml {
        constructor(count) {
            this.count = count;
            this.dom = null;
            this.destroy_button = null;
            this.buildDom();
        }

        buildDom() {
            this.dom = document.createElement('div');
            const receiver_name = document.createElement('input');
            $(receiver_name)
                .attr('type', 'text')
                .attr('placeholder', 'Receiver name')
                .attr('required', 'required')
                .attr('name', `receivers_name[${this.count}]`)
                .attr('id', `receivers_name[${this.count}]`)
                .addClass('form-control m');
            const name_label = document.createElement('label');
            $(name_label)
                .addClass('col-lg-3 col-xs-12 required')
                .attr('for', `receivers_name[${this.count}]`)
                .css({'margin': 0})
                .text('Name');
            const name_dom = document.createElement('div');
            $(name_dom)
                .addClass('col-md-5')
                .append(name_label, receiver_name);

            const receiver_email = document.createElement('input');
            $(receiver_email)
                .attr('type', 'email')
                .attr('placeholder', 'Receiver Email')
                .attr('required', 'required')
                .attr('name', `receivers_email[${this.count}]`)
                .attr('id', `receivers_email[${this.count}]`)
                .addClass('form-control m');
            const email_label = document.createElement('label');
            $(email_label)
                .addClass('col-lg-3 col-xs-12 required')
                .attr('for', `receivers_email[${this.count}]`)
                .css({'margin': 0})
                .text('Email');
            const email_dom = document.createElement('div');
            $(email_dom)
                .addClass('col-md-5')
                .append(email_label, receiver_email);

            this.destroy_button = document.createElement('button');
            $(this.destroy_button)
                .attr('type', 'button')
                .addClass('btn btn-danger m')
                .attr('data-predestination', 'delete_button')
                .attr('data-id', this.count)
                .text('Delete');
            const destroy_dom = document.createElement('div');
            $(destroy_dom)
                .addClass('col-md-1')
                .append(this.destroy_button);

            $(this.dom)
                .addClass('row')
                .attr('data-dom-id', this.count)
                .append(name_dom, email_dom, destroy_dom);

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

    $(addReceiver).on('click', function (e) {
        ++receiverCount;
        const parent = e.target.offsetParent;
        $(parent).find('.receiver_block').append(new ReceiverHtml(receiverCount).getDom());
    });

    $(document).on('click', '[data-predestination=delete_receiver]', function () {
        $('.receiver_block').find(`[data-dom-id=${$(this).attr('data-id')}]`).remove();
    });

    $(document).on('submit', '#contactUsForm', function () {
        $(this).find(':input[type=submit]')
            .attr('disabled', true)
            .removeClass('btn-success')
            .addClass('btn-danger');

    });
});