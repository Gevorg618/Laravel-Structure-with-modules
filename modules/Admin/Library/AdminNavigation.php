<?php

namespace Modules\Admin\Library;

class AdminNavigation
{
  public static function getAdminNavigation() {
    $links = static::_getAdminNavigation();

    $columns = [];
    foreach($links as $id => $link) {
      if(!$link['pos']) {
        $link['pos'] = 'left';
      }
      $columns[$link['pos']][$id] = $link;
    }
    return $columns;
  }

  protected static function _getAdminNavigation() {
    return [
        "statusselect" => [
            "title" => "Status Select",
            "visible" => true,
            "perms" => true,
            "items" => [
                [
                    "title" => "Appraisal Dashboard (0)",
                    "url" => "/admin/dashboardstart.php",
                    "perms" => true,
                    "visible" => true
                ]
            ],
            "pos" => "left",
            "class" => "default"
        ],
        "apprpipeline" => [
            "title" => "Appraisal Pipeline",
            "visible" => true,
            "perms" => true,
            "items" => [
                [
                    "title" => "Company Pipeline (694)",
                    "url" => "/admin/pipelines.php",
                    "perms" => true,
                    "visible" => true
                ],
                [
                    "html" => '<div class="hr-line-dashed"></div>'
                ],
                [
                    "title" => "Escalated Orders Pipeline (16)",
                    "url" => route('admin.appraisal-pipeline.escalated-orders'),
                    "visible" => true,
                    "perms" => true
                ],
                [
                    "title" => "Delayed Orders Pipeline (4)",
                    "url" => route('admin.appraisal-pipeline.delayed-pipeline'),
                    "visible" => true,
                    "perms" => true
                ],
                [
                    "title" => "Purchase & New Construction Pipeline (87)",
                    "url" => route('admin.appraisal-pipeline.purchase-pipeline'),
                    "visible" => true,
                    "perms" => true
                ],
                [
                    "title" => "Unassigned Pipeline (19)",
                    "url" => route('admin.appraisal-pipeline.unassigned-pipeline'),
                    "visible" => true,
                    "perms" => true
                ]
            ],
            "pos" => "left",
            "class" => "default"
        ],
        "docuvaultpipeline" => [
            "title" => "External DocuVault Pipeline",
            "visible" => true,
            "perms" => true,
            "items" => [
                [
                    "title" => "Company Pipeline (8,442)",
                    "url" => "/admin/docuvault_pipeline.php",
                    "perms" => true,
                    "visible" => true
                ]
            ],
            "pos" => "left",
            "class" => "default"
        ],
        "alpipeline" => [
            "title" => "Alternative Valuation Pipeline",
            "visible" => false,
            "perms" => true,
            "items" => [
                [
                    "title" => "Company Pipeline (0)",
                    "url" => "/admin/alpipelines.php",
                    "perms" => true,
                    "visible" => true
                ],
                [
                    "title" => "  Alt Val - MarkIt Value / TriMerge Pipeline (0)",
                    "url" => "/admin/alpipelines.php?team=165",
                    "visible" => true
                ]
            ],
            "pos" => "left",
            "class" => "default"
        ],
        "postcompletionpipeline" => [
            "title" => "Post Completion Pipelines",
            "visible" => true,
            "perms" => true,
            "items" => [
                [
                    "title" => "Approve Finished Appraisals (4)",
                    "url" => "/admin/appr_qc_pipeline.php",
                    "visible" => true,
                    "perms" => true
                ],
                [
                    "title" => "Approve U/W Appraisals (4)",
                    "url" => route('admin.post-completion-pipelines.appr-uw-pipeline'),
                    "visible" => true,
                    "perms" => true
                ],
                [
                    "title" => "Reconsideration Pipeline (16)",
                    "url" => route('admin.post-completion-pipelines.review-pipeline'),
                    "visible" => true,
                    "perms" => true
                ],
                [
                    "title" => "Alternative Valuation QC (0)",
                    "url" => "/admin/al_qcpipeline.php",
                    "visible" => true,
                    "perms" => true
                ],
                [
                    "title" => "Final Appraisals to be Mailed (2)",
                    "url" => route('admin.post-completion-pipelines.mail-pipeline'),
                    "visible" => true,
                    "perms" => true
                ]
            ],
            "pos" => "left",
            "class" => "default"
        ],
        "supportpipeline" => [
            "title" => "Support Pipeline",
            "visible" => false,
            "perms" => true,
            "items" => [

            ],
            "pos" => "middle",
            "class" => "default"
        ],
        "ticketmanager" => [
            "title" => "Support Tickets",
            "visible" => true,
            "perms" => true,
            "items" => [
                [
                    "title" => "Tickets Manager",
                    "url" => route('admin.ticket.manager'),
                    "perms" => true
                ],
                [
                    "title" => "Tickets Statistics",
                    "url" => route('admin.ticket.stats.index'),
                    "visible" => true,
                    "perms" => true,
                    "html" => '<div class="hr-line-dashed"></div>'
                ],
                [
                    "title" => "Ticket Rules",
                    "url" => route('admin.ticket.rule'),
                    "perms" => true
                ],
                [
                    "title" => "Ticket Statuses",
                    "url" => route('admin.ticket.statuses.index'),
                    "perms" => true
                ],
                [
                    "title" => "Ticket Categories",
                    "url" => "/admin/ticket/categories",
                    "perms" => true
                ],
                [
                    "title" => "Ticket Multi-Moderation",
                    "url" => route('admin.ticket.moderation'),
                    "perms" => true
                ]
            ],
            "pos" => "middle",
            "class" => "default"
        ],
        "accounting" => [
            "title" => "Accounting",
            "visible" => true,
            "perms" => true,
            "items" => [
                [
                    "title" => "Accounts Payable Report",
                    "url" => route('admin.accounting.payable-reports.index'),
                    "visible" => true,
                    "perms" => true
                ],
                [
                    "title" => "AL Accounts Payable Report",
                    "url" => route('admin.accounting.al-payable-reports.index'),
                    "visible" => true,
                    "perms" => true
                ],
                [
                    "title" => "Accounts Receivable Report",
                    "url" => route('admin.accounting.receivable-reports.index'),
                    "visible" => true,
                    "perms" => true
                ],
                [
                    "title" => "Accounting General Reports",
                    "url" => route('admin.accounting.general-reports.index'),
                    "visible" => true,
                    "perms" => true
                ],
                [
                    "title" => "Export Checks",
                    "url" => route('admin.accounting.export-check.index'),
                    "visible" => true,
                    "perms" => true
                ],
                [
                    "title" => "Accounting Reports",
                    "url" => route('admin.accounting.reports.index'),
                    "visible" => true,
                    "perms" => true,
                    "html" => '<div class="hr-line-dashed"></div>'
                ],
                [
                    "title" => "PayView Report Generator",
                    "url" => "/admin/payview.php",
                    "visible" => true,
                    "perms" => true
                ],
                [
                    "title" => "Lookup Checks Sent/Recv",
                    "url" => route('admin.accounting.locate-payments.index'),
                    "visible" => true,
                    "perms" => true
                ],
                [
                    "title" => "Daily Batch",
                    "url" => route('admin.accounting.daily-batch.index'),
                    "visible" => true,
                    "perms" => true
                ],
                [
                    "title" => "Batch Payments",
                    "url" => route('admin.accounting.batch-check.index'),
                    "visible" => true,
                    "perms" => true
                ],
                [
                    "title" => "DocuVault Batch Payments",
                    "url" => route('admin.accounting.batch-docuvault-check.index'),
                    "visible" => true,
                    "perms" => true
                ],
                [
                    "html" => '<div class="hr-line-dashed"></div>'
                ],
                [
                    "title" => "Saved Sungard Transactions",
                    "url" => route('admin.accounting.payable.index'),
                    "visible" => true,
                    "perms" => true
                ],
                [
                    "html" => NULL
                ],
                [
                    "title" => "Accounts Payable Manager",
                    "url" => route('admin.accounting.payable-manager.index'),
                    "visible" => true,
                    "perms" => true
                ],
                [
                    "title" => "Accounts Payable Revert",
                    "url" => route('admin.accounting.payable-revert.index'),
                    "visible" => true,
                    "perms" => true
                ],
                [
                    "title" => "DocuVault Receivables",
                    "url" => route('admin.accounting.docuvault-receivables.index'),
                    "visible" => true,
                    "perms" => true
                ],
                [
                    "title" => "Payables",
                    "url" => route('admin.accounting.payable.index'),
                    "visible" => true,
                    "perms" => true
                ],
                [
                    "title" => "Vendor Tax Info",
                    "url" => route('admin.vendor_tax_info.index'),
                    "visible" => true,
                    "perms" => true
                ],
            ],
            "pos" => "middle",
            "class" => "default"
        ],
        "orderproperties" => [
            "title" => "Customizations",
            "visible" => true,
            "perms" => true,
            "items" => [
                [
                    "title" => "Appraisal Types",
                    "url" => route('admin.appraisal.appr-types.index'),
                    "perms" => true
                ],
                [
                    "title" => "Appraisal Access Types",
                    "url" => route('admin.appraisal.access_type.index'),
                    "perms" => true
                ],
                [
                    "title" => "Appraisal Order Statuses",
                    "url" => route('admin.appraisal.appr-statuses.index'),
                    "perms" => true
                ],
                [
                    "title" => "Appraisal Loan Reasons",
                    "url" => route('admin.appraisal.loanreason'),
                    "perms" => true
                ],
                [
                    "title" => "Appraisal Loan Types",
                    "url" => route('admin.appraisal.loantype'),
                    "perms" => true
                ],
                [
                    "title" => "Appraisal Occupancy Statuses",
                    "url" => route('admin.appraisal.occupancy.status'),
                    "perms" => true
                ],
                [
                    "title" => "Appraisal Property Types",
                    "url" => route('admin.appraisal.property-types.index'),
                    "perms" => true
                ],
                [
                    "title" => "Appraisal Addendas",
                    "url" => route('admin.appraisal.addendas'),
                    "perms" => true,
                    "html" => '<div class="hr-line-dashed"></div>'
                ],
                [
                    "title" => "DocuVault Appraisal Types",
                    "url" => route('admin.docuvault.appraisal.index'),
                    "perms" => true
                ],
                [
                    "title" => "Alternative Valuation Order Statuses",
                    "url" => route('admin.valuation.orders.status'),
                    "perms" => true,
                    "html" => '<div class="hr-line-dashed"></div>'
                ],
                [
                    "title" => "Delay Codes Manager",
                    "url" => route('admin.appraisal.delay-codes'),
                    "perms" => true,
                ],
                [
                    "title" => "Sales Tax",
                    "url" => route('admin.management.sale.tax.index'),
                    "perms" => true
                ],
                [
                    "title" => "AMC State Registrations",
                    "url" => route('admin.management.amc-licenses'),
                    "perms" => true,
                    "html" => '<div class="hr-line-dashed"></div>'
                ],
                [
                    "title" => "Client Bulk Change Tool",
                    "url" => route('admin.management.client-bulk-tool'),
                    "perms" => true
                ],
                [
                    "title" => "Turn Time by State",
                    "url" => route('admin.management.turn-time-by-state'),
                    "perms" => true
                ]
            ],
            "pos" => "middle",
            "class" => "default"
        ],
        "managerreports" => [
            "title" => "Manager Reports",
            "visible" => true,
            "perms" => true,
            "items" => [
                [
                    "title" => "Report Generator",
                    "url" => route('admin.reports.generator.index'),
                    "visible" => true,
                    "perms" => true
                ],
                [
                    "title" => "DocuVault Report Generator",
                    "url" => route('admin.reports.docu.vault.index'),
                    "visible" => true,
                    "perms" => true
                ],
                [
                    "title" => "QC Report",
                    "url" => route('admin.manager-reports.qc-report'),
                    "visible" => true,
                    "perms" => true
                ],
                [
                    "title" => "Tasks",
                    "url" => route('admin.reports.tasks.index'),
                    "visible" => true,
                    "perms" => true
                ],
                [
                    "title" => "User Report Generator",
                    "url" => route('admin.reports.user.generator.index'),
                    "visible" => true,
                    "perms" => true
                ],
                [
                    "title" => "Reconsideration Report",
                    "url" => route('admin.reports.reconsideration.index'),
                    "visible" => true,
                    "perms" => true
                ],
                [
                    "title" => "Client Setting Reports",
                    "url" => route('admin.reports.client.setting.index'),
                    "visible" => true,
                    "perms" => true
                ],
            ],
            "pos" => "right",
            "class" => "default"
        ],
        "crm" => [
            "title" => "Landscape CRM",
            "visible" => true,
            "perms" => true,
            "items" => [
                [
                    "title" => "Leads Manager (10,243)",
                    "url" => "/admin/leads.php",
                    "visible" => true,
                    "perms" => true
                ],
                [
                    "title" => "Create New Lead",
                    "url" => "/admin/leads.php?action=create-lead",
                    "visible" => true,
                    "perms" => true
                ],
                [
                    "title" => "Lead Reporting",
                    "url" => "/admin/lead_reports.php",
                    "visible" => true,
                    "perms" => true
                ],
                [
                    "title" => "Sales Stages",
                    "url" => route('admin.crm.sale.stages.index'),
                    "visible" => true,
                    "perms" => true
                ]
            ],
            "pos" => "right",
            "class" => "default"
        ],
        "statistics" => [
            "title" => "Statistics & User Tracking",
            "visible" => true,
            "perms" => true,
            "items" => [
                [
                    "title" => "Statistics (0)",
                    "url" => route('admin.statistics.index'),
                    "visible" => true,
                    "perms" => true
                ],
                [
                    "title" => "Big Stats",
                    "url" => route('admin.statistics.big.index', ['date' => date('Y-m-d')]),
                    "visible" => true,
                    "perms" => true
                ],
                [
                    "title" => "Accounting Big Stats",
                    "url" => route('admin.statistics.accounting-big.index'),
                    "visible" => true,
                    "perms" => true
                ],
                [
                    "title" => "Dashboard Stats",
                    "url" => route('admin.statistics.dashboard.index'),
                    "visible" => true,
                    "perms" => true
                ],
                [
                    "title" => "Sales Commission Report",
                    "url" => route('admin.statistics.sales-commission.index'),
                    "perms" => true,
                    "visible" => true
                ],
                [
                    "title" => "Average Delay Codes",
                    "url" => route('admin.statistics.avg-delay-codes'),
                    "perms" => true,
                    "visible" => true
                ],
                [
                    "title" => "Status Select Statistics",
                    "url" => route('admin.statistics.status-select.index'),
                    "perms" => true,
                    "visible" => true
                ],
                [
                    "title" => "System Statistics",
                    "url" => route('admin.statistics.system-statistics'),
                    "perms" => true,
                    "visible" => true,
                    "html" => '<div class="hr-line-dashed"></div>'
                ],
                [
                    "title" => "User Logins",
                    "url" => route('admin.statistics.user.logins'),
                    "visible" => true,
                    "perms" => true
                ],
                [
                    "url" => route('admin.statistics.user-logs'),
                    "title" => "User Logs",
                    "visible" => true,
                    "perms" => true
                ]
            ],
            "pos" => "right",
            "class" => "default"
        ],
        "integrations" => [
            "title" => "Integrations",
            "visible" => true,
            "perms" => true,
            "items" => [
                [
                    "title" => "API Users",
                    "url" => route('admin.integrations.api-users'),
                    "visible" => true,
                    "perms" => true,
                    "html" => '<div class="hr-line-dashed"></div>'
                ],
                [
                    "title" => "Mercury Network",
                    "url" => "/admin/integrations/mercury",
                    "visible" => true,
                    "perms" => true
                ],
                [
                    "title" => "FNC",
                    "url" => "/admin/integrations/fnc",
                    "visible" => true,
                    "perms" => true
                ],
                [
                    "title" => "Ditech",
                    "url" => route('admin.reports.ditech.index'),
                    "visible" => true,
                    "perms" => true,
                    "html" => '<div class="hr-line-dashed"></div>'
                ],
                [
                    "title" => "Google API",
                    "url" => route('admin.integrations.google'),
                    "visible" => true,
                    "perms" => true
                ]
            ],
            "pos" => "right",
            "class" => "default"
        ],
        "tiger" => [
            "title" => "Tiger",
            "visible" => true,
            "perms" => true,
            "items" => [
                [
                    "title" => "Clients",
                    "url" => route('admin.tiger.clients.index'),
                    "visible" => true,
                    "perms" => true,
                ],
                [
                    "title" => "AMCs",
                    "url" => route('admin.tiger.amcs.index'),
                    "visible" => true,
                    "perms" => true
                ],
                [
                    "title" => "Permissions",
                    "url" => "",
                    "visible" => true,
                    "perms" => true,
                ],
            ],
            "pos" => "right",
            "class" => "warning"
        ],
        "usertracking" => [
            "title" => "User Tracking",
            "visible" => false,
            "perms" => true,
            "items" => [

            ],
            "pos" => "right",
            "class" => "default"
        ],
        "adminuser" => [
            "title" => "Admin User",
            "visible" => true,
            "perms" => true,
            "items" => [
                [
                    "title" => "User Manager",
                    "url" => route('admin.users.index'),
                    "visible" => true,
                    "perms" => true
                ],
                [
                    "title" => "Client Settings",
                    "url" => route('admin.management.client.settings'),
                    "visible" => true,
                    "perms" => true
                ],
                [
                    "title" => "Wholesale Lenders",
                    "url" => route('admin.management.lenders'),
                    "perms" => true,
                    "html" => '<div class="hr-line-dashed"></div>'
                ],
                [
                    "title" => "User Groups",
                    "url" => route('admin.management.groups.index'),
                    "visible" => true,
                    "perms" => true
                ],
                [
                    "title" => "Admin Groups",
                    "url" => route('admin.management.admin-groups'),
                    "visible" => true,
                    "perms" => true
                ],
                [
                    "title" => "Appraiser Groups",
                    "url" => route('admin.management.appraiser.index'),
                    "visible" => true,
                    "perms" => true
                ],
                [
                    "title" => "Admin Teams Manager",
                    "url" => route('admin.management.admin-teams-manager'),
                    "visible" => true,
                    "perms" => true,
                    "html" => '<div class="hr-line-dashed"></div>'
                ],
                [
                    "title" => "Appraiser Map",
                    "url" => "/admin/assign.php",
                    "perms" => true
                ],
                [
                    "title" => "Active Users",
                    "url" => route('admin.management.active-users'),
                    "visible" => true,
                    "perms" => true,
                    "html" => '<div class="hr-line-dashed"></div>'
                ],
                [
                    "title" => "FHA Appraiser Licenses",
                    "url" => route('admin.management.fha-licenses.index'),
                    "perms" => true
                ],
                [
                    "title" => "Lenders Exclusionary List",
                    "url" => route('admin.lenders.exclusionary'),
                    "perms" => true
                ],
                [
                    "title" => "ASC Appraiser List",
                    "url" => route('admin.management.asc-licenses'),
                    "perms" => true
                ],
                [
                    "title" => "Google Geo Coding (0)",
                    "url" => route('admin.geo.google-coding.index'),
                    "perms" => true
                ],
                [
                    "title" => "GEO Code Addresses",
                    "url" => route('admin.geo.address'),
                    "perms" => true
                ]
            ],
            "pos" => "rightouter",
            "class" => "danger"
        ],
        "adminlists" => [
            "title" => "Admin Lists",
            "visible" => true,
            "perms" => true,
            "items" => [
                [
                    "title" => "Zip Code Manager",
                    "url" => route('admin.management.zipcodes'),
                    "visible" => true,
                    "perms" => true
                ],
                [
                    "title" => "Email Templates",
                    "url" => route('admin.management.email-templates'),
                    "visible" => true,
                    "perms" => true
                ],
                [
                    "title" => "Custom Emails",
                    "url" => route('admin.management.custom-email-templates'),
                    "visible" => true,
                    "perms" => true
                ],
                [
                    "title" => "User Templates",
                    "url" => route('admin.management.user-templates'),
                    "visible" => true,
                    "perms" => true
                ],
                [
                    "title" => "Emails Sent",
                    "url" => route('admin.tools.emails-sent'),
                    "visible" => true,
                    "perms" => true
                ]
            ],
            "pos" => "rightouter",
            "class" => "danger"
        ],
        "controlpanel" => [
            "title" => "Control Panel",
            "visible" => true,
            "perms" => true,
            "items" => [
                [
                    "title" => "Announcements",
                    "url" => route('admin.management.announcements'),
                    "perms" => true
                ],
                [
                    "title" => "Surveys",
                    "url" => route('admin.management.surveys.index'),
                    "perms" => true
                ],
                [
                    "title" => "Survey Reporting",
                    "url" => route('admin.management.surveys.answers.index'),
                    "perms" => true,
                    "html" => '<div class="hr-line-dashed"></div>'
                ],
                [
                    "title" => "States Compliance",
                    "url" => "/admin/state_compliance.php",
                    "perms" => true,
                    "html" => '<div class="hr-line-dashed"></div>'
                ],
                [
                    "title" => "User Order Transfers",
                    "url" => route('admin.tools.user-order-transfers.index'),
                    "perms" => true,
                    "html" => '<div class="hr-line-dashed"></div>'
                ],
                [
                    "title" => "QC Checklist Editor",
                    "url" => route('admin.qc.checklist.index'),
                    "perms" => true
                ],
                [
                    "title" => "QC Data Collection Editor",
                    "url" => route('admin.qc.collection.index'),
                    "perms" => true
                ],
                [
                    "title" => "UW Checklist Editor",
                    "url" => route('admin.appraisal.under-writing.checklist'),
                    "perms" => true
                ],
                [
                    "title" => "UCDP Business Units",
                    "url" => route('admin.appraisal.ucdp-unit'),
                    "perms" => true
                ],
                [
                    "title" => "EAD Business Units",
                    "url" => route('admin.appraisal.ead-unit'),
                    "perms" => true,
                    "html" => '<div class="hr-line-dashed"></div>'
                ],
                [
                    "title" => "Targus Info",
                    "url" => "/admin/targus.php",
                    "perms" => true,
                    "visible" => true
                ],
                [
                    "title" => "Shipping Labels",
                    "url" => route('admin.tools.shipping-labels'),
                    "perms" => true
                ],
                [
                    "title" => "Keys Legend",
                    "url" => route('admin.tools.keys-legend'),
                    "perms" => true
                ]
            ],
            "pos" => "rightouter",
            "class" => "danger"
        ],
        "autoselectandpricing" => [
            "title" => "AutoSelect & Pricing",
            "visible" => true,
            "perms" => true,
            "items" => [
                [
                    "title" => "Pricing Versions",
                    "url" => route('admin.autoselect.pricing.versions.index'),
                    "perms" => true
                ],
                [
                    "title" => "AutoSelect Counties",
                    "url" => "/admin/autoselect-pricing/counties",
                    "perms" => true
                ],
                [
                    "title" => "AutoSelect Appraiser Fees",
                    "url" => route('admin.autoselect.appraiser.fees.index'),
                    "perms" => true
                ],
                [
                    "title" => "AutoSelect Pricing Version Fees",
                    "url" => route('admin.autoselect.pricing.fees.index'),
                    "perms" => true
                ],
                [
                    "title" => "AutoSelect Turn Times",
                    "url" => route('admin.autoselect.turn.times.index'),
                    "perms" => true
                ]
            ],
            "pos" => "rightouter",
            "class" => "danger"
        ],
        "documentsanduploads" => [
            "title" => "Documents & Uploads",
            "visible" => true,
            "perms" => true,
            "items" => [
                [
                    "title" => "Document Types Manager",
                    "url" => route('admin.document.types'),
                    "perms" => true
                ],
                [
                    "title" => "User Document Types Manager",
                    "url" => route('admin.document.user.types'),
                    "perms" => true
                ],
                [
                    "title" => "Global Documents",
                    "url" => route('admin.document.global.index'),
                    "perms" => true
                ],
                [
                    "title" => "Resource Documents Manager",
                    "url" => route('admin.document.resource'),
                    "perms" => true
                ],
                [
                    "title" => "Upload Manager",
                    "url" => route('admin.document.upload'),
                    "perms" => true
                ]
            ],
            "pos" => "rightouter",
            "class" => "danger"
        ],
        "settingsandtemplates" => [
            "title" => "Settings & Templates",
            "visible" => true,
            "perms" => true,
            "items" => [
                [
                    "title" => "Settings Manager",
                    "url" => route('admin.tools.settings'),
                    "perms" => true
                ],
                [
                    "title" => "Templates Manager",
                    "url" => route('admin.tools.templates'),
                    "perms" => true
                ],
                [
                    "title" => "Geo Manager",
                    "url" => route('admin.tools.geo.index'),
                    "perms" => true
                ],
                [
                    "title" => "Logo Manager",
                    "url" => route('admin.tools.logos'),
                    "perms" => true
                ],
            ],
            "pos" => "rightouter",
            "class" => "danger"
        ],
        "frontendsite" => [
            "title" => "Front-End Site",
            "visible" => true,
            "perms" => true,
            "items" => [
                [
                    "title" => "Header Carousel",
                    "url" => route('admin.frontend-site.header-carousel.index'),
                    "perms" => true
                ],
                [
                    "title" => "Team Members",
                    "url" => route('admin.frontend-site.team-member.index'),
                    "perms" => true
                ],
                [
                    "title" => "Service We Provide",
                    "url" => route('admin.frontend-site.services.index'),
                    "perms" => true
                ],
                [
                    "title" => " Navigation Menu",
                    "url" => route('admin.frontend-site.navigation-menu.index'),
                    "perms" => true
                ],
                [
                    "title" => "Stats",
                    "url" => route('admin.frontend-site.stats.index'),
                    "perms" => true
                ],

                [
                    "title" => "Latest News",
                    "url" => route('admin.frontend-site.latest-news.index'),
                    "perms" => true
                ],
                [
                    "title" => "Client Testimonials",
                    "url" => route('admin.frontend-site.client-testimonials.index'),
                    "perms" => true
                ],
                [
                    "title" => "Custom Pages",
                    "url" => route('admin.tools.custom-pages-manager.index'),
                    "perms" => true
                ],
            ],
            "pos" => "rightouter",
            "class" => "danger"
        ],
    ];
  }
}
