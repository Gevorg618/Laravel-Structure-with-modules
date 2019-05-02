const path = require('path');

module.exports = {
    title: 'Legacy API Documentation',
    description: ' ',
    dest: path.resolve('public/build/legacyapi/docs'),
    serviceWorker: true,
    themeConfig: {
        nav: [{
            text: 'Landmark',
            link: 'https://landmarknetwork.com'
        }],
        sidebar: [
            ['/', 'Home'],
            ['/overview/', 'Overview'],
            ['/changelog/', 'Changelog'],
            ['/passing_parameters/', 'Passing Parameters'],
            ['/error_codes/', 'Error Codes'],
            {
                title: 'Users',
                children: [
                    ['/users/', 'Fields'],
                    ['/users/list_users', 'List Users'],
                    ['/users/add_or_update_users', 'Add Or Update Users'],
                    ['/users/view_user_information', 'View User Information'],
                    ['/users/authenticate_user', 'Authenticate User'],
                ]
            },
            {
                title: 'Groups',
                children: [
                    ['/groups/', 'Overview'],
                    ['/groups/list_groups', 'List Groups'],
                ]
            },
            {
                title: 'Orders Client',
                children: [
                    ['/orders_client/', 'Fields'],
                    ['/orders_client/list_client_orders', 'List Client Orders'],
                    ['/orders_client/add_or_update_order', 'Add Or Update Order'],
                    ['/orders_client/get_order_log_entries', 'Get Order Log Entries'],
                    ['/orders_client/view_order_information', 'View Order Information'],
                    ['/orders_client/view_order_related_information', 'View Order Related Information'],
                    ['/orders_client/upload_order_document', 'Upload Order Document'],
                    ['/orders_client/get_final_report', 'Get Final Report'],
                    ['/orders_client/get_order_invoice_or_icc_documents', 'Get Order Invoice Or ICC Documents'],
                    ['/orders_client/get_order_public_documents', 'Get Order Public Documents'],
                    ['/orders_client/add_support_ticket', 'Add Support Ticket'],
                    ['/orders_client/get_client_order_options', 'Get Client Order Options'],
                    ['/orders_client/view_client_pricing_information', 'View Client Pricing Information'],
                ]
            },
            {
                title: 'Orders Vendor',
                children: [
                    ['/orders_vendor/', 'Overview'],
                    ['/orders_vendor/list_orders', 'List Orders'],
                    ['/orders_vendor/upload_final_report', 'Upload Final Report'],
                    ['/orders_vendor/add_support_ticket', 'Add Support Ticket'],
                ]
            },
            {
                title: 'Subscribers',
                children: [
                    ['/subscribers/', 'Overview'],
                    ['/subscribers/list', 'List'],
                    ['/subscribers/add', 'Add'],
                    ['/subscribers/update', 'Update'],
                    ['/subscribers/delete', 'Delete'],
                    ['/subscribers/examples', 'Examples'],
                ]
            },
        ]
    }
}
