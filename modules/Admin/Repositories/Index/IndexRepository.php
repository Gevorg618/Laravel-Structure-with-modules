<?php

namespace Modules\Admin\Repositories\Index;

use App\Models\Calendar\UserGroupLead;
use App\Models\Calendar\CalendarEvent;
use App\Models\Calendar\CalendarEventUser;
use App\Models\Users\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class IndexRepository
{
    /**
     * @return mixed
     */
    public function getAnnouncements()
    {
        return DB::table('announcement')->select('announcement.*')
            ->leftJoin('announcement_visible', 'announcement.id', '=', 'announcement_visible.message_id')
            ->where('announcement_visible.user_type', 1)->orderBy('created_date', 'desc')->get();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getAnnouncement($id)
    {
        return DB::table('announcement')->where('id', $id)->first();
    }

    /**
     * @return array
     */
    public function getCalendarData()
    {
        return [
            'followups' => [
                'key' => 'followups',
                'title' => 'Follow Ups',
                'color' => 'green',
                'textColor' => 'white',
            ],
            'company' => [
                'key' => 'company',
                'title' => 'Company',
                'color' => 'blue',
                'textColor' => 'white',
            ],
            'meetings' => [
                'key' => 'meetings',
                'title' => 'Meetings',
                'color' => 'cyan',
                'textColor' => 'black',
            ],
            'private' => [
                'key' => 'private',
                'title' => 'Private',
                'color' => 'purple',
                'textColor' => 'white',
            ],
            'holidays' => [
                'key' => 'holidays',
                'title' => 'Holidays',
                'color' => 'pink',
                'textColor' => 'black',
            ],
        ];
    }

    /**
     * @return array
     */
    public function getCalendarAdminUserList()
    {
        $admins = User::select('user.id', 'user_data.firstname', 'user_data.lastname')
            ->leftJoin('user_data', 'user.id', '=', 'user_data.user_id')
            ->where('user.user_type', 1)->where('user.active', 'Y')
            ->orderby('user_data.firstname', 'user.data.lastname')->get();
        $list = [];
        if ($admins) {
            foreach ($admins as $admin) {
                $list[$admin->id] = trim($admin->firstname . ' ' . $admin->lastname);
            }
        }

        return $list;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getCalendarEvent($id)
    {
        $row = CalendarEvent::where('id', $id)->first();
        $row->start_date = Carbon::createFromTimestamp($row->start_date)->format('YY-m-d g:i A ');
        $row->end_date = Carbon::createFromTimestamp($row->end_date)->format('YY-m-d g:i A');
        $row->calendar = $this->getCalendarDataByKey($row->calendar);
        return $row;
    }

    /**
     * @param $start
     * @param $end
     * @return array
     */
    public function getCalendarEvents($start, $end)
    {
        $events = [];

        $followups = $this->getLeadsFollowUps($start, $end, getUserId());
        if ($followups) {
            foreach ($followups as $r) {
                $events[] = [
                    'url' => '/admin/leads/update-lead/' . $r->id,
                    'title' => $r->company_name . ' @ ' . Carbon::createFromTimestamp($r->follow_up_date)->format('g:i A'),
                    'start' => Carbon::createFromTimestamp($r->follow_up_date)->format('g:i A'),
                    'end' => $r->follow_up_date,
                    'color' => 'green',
                    'textColor' => 'white',
                ];
            }
        }

        $private = $this->getUserPrivateEvents($start, $end);
        $privateCalendarData = $this->getCalendarDataByKey('private');
        if ($private) {
            foreach ($private as $r) {
                $events[] = [
                    'id' => 'event_' . $r->id,
                    'className' => 'view_event',
                    'title' => $r->title,
                    'start' => Carbon::createFromTimestamp($r->start_date)->format('Y/m/d'),
                    'end' => Carbon::createFromTimestamp($r->end_date)->format('Y/m/d'),
                    'allDay' => $r->all_day,
                    'color' => $privateCalendarData['color'],
                    'textColor' => $privateCalendarData['textColor'],
                ];
            }
        }

        $other = $this->getCalendarPublicEvents($start, $end);
        if ($other) {
            foreach ($other as $r) {
                $calendarData = $this->getCalendarDataByKey($r->calendar);
                $events[] = [
                    'id' => 'event_' . $r->id,
                    'className' => 'view_event',
                    'title' => $this->getEventCalendarTitle($r),
                    'start' => Carbon::createFromTimestamp($r->start_date)->format('Y/m/d'),
                    'end' => Carbon::createFromTimestamp($r->end_date)->format('Y/m/d'),
                    'allDay' => $r->all_day,
                    'color' => $calendarData['color'],
                    'textColor' => $calendarData['textColor'],
                ];
            }
        }

        return $events;
    }

    /**
     * @param $r
     * @return string
     */
    private function getEventCalendarTitle($r)
    {
        $title = $r->title;
        if (strlen($r->title) > 30) {
            $title = (substr($r->title, 0, 30) . '...');
        }
        $title .= " @ " . (Carbon::createFromTimestamp($r->start_date)->format('g:i A'));
        return $title;
    }

    /**
     * @param $start
     * @param $end
     * @param $userId
     * @return mixed
     */
    private function getLeadsFollowUps($start, $end, $userId)
    {
        return UserGroupLead::select('id', 'company_name', 'follow_up_date')->where('follow_up_date', '>=', $start)
            ->where('follow_up_date', '<=', $end)->where('follow_up_user', $userId)->get();
    }

    /**
     * @param $start
     * @param $end
     * @param null $userId
     * @return mixed
     */
    private function getUserPrivateEvents($start, $end, $userId = null)
    {
        $st = Carbon::parse($start)->timestamp;
        $en = Carbon::parse($end)->timestamp;
        $id = $userId ? $userId : getUserId();
        return CalendarEvent::where('start_date', '>=', $st)->where('end_date', '<=', $en)
            ->where('is_private', 1)->where('created_by', $id)->get();
    }

    /**
     * @param $key
     * @return mixed
     */
    private function getCalendarDataByKey($key)
    {
        $calendars = $this->getCalendarData();
        return isset($calendars[$key]) ? $calendars[$key] : $calendars['company'];
    }

    /**
     * @param $start
     * @param $end
     * @param null $userId
     * @return array
     */
    private function getCalendarPublicEvents($start, $end, $userId = null)
    {
        $id = $userId ? $userId : getUserId();
        $rows = CalendarEvent::where('start_date', '>=', strtotime($start))->where('end_date', '<=', strtotime($end))
            ->where('is_private', 0)->get();
        $list = [];
        if ($rows) {
            foreach ($rows as $row) {
                $canView = false;
                if ($row->created_by == $id) {
                    $canView = true;
                } else {
                    $visibleTo = $this->getCalendarEventUsersVisible($row->id);
                    if (!$visibleTo) {
                        $canView = true;
                    } else {
                        foreach ($visibleTo as $user) {
                            if ($user->user_id == $id) {
                                $canView = true;
                                break;
                            }
                        }
                    }
                }

                if ($canView) {
                    $list[] = $row;
                }
            }
        }

        return $list;
    }

    /**
     * @param $eventId
     * @return mixed
     */
    public function getCalendarEventUsersVisible($eventId)
    {
        return CalendarEventUser::select('user_id')->where('event_id', $eventId)->get();
    }

    /**
     * @param $id
     * @return array
     */
    public function getVisibleUsersList($id)
    {
        $visibleUsers = $this->getCalendarEventUsersVisible($id);
        $list = [];

        if ($visibleUsers) {
            foreach ($visibleUsers as $user) {
                $list[$user->user_id] = $user->user_id;
            }
        }

        return $list;
    }

    /**
     * @param $id
     * @param $data
     * @return array
     */
    public function updateEvent($id, $data)
    {
        $row = $this->getCalendarEvent($id);
        if (!$row) {
            return ['error' => 'Sorry, That event was not found.'];
        }
        if (!isAdmin() && $row->created_by != getUserId()) {
            return ['error' => 'Sorry, You do not have the permission to edit this event.'];
        }
        $title = $data['event_title'];
        $start = $data['event_start_date'];
        $end = $data['event_end_date'];
        $allDay = $data['all_day'];
        $isPrivate = $data['event_private'];
        $calendar = $data['event_calendar_id'];
        $content = $data['event_content'];
        $users = isset($data['users']) ? $data['users'] : null;
        if (!$title) {
            return ['error' => 'Sorry, You must provide a title.'];
        }
        if (!$start) {
            return ['error' => 'Sorry, You must provide a start date.'];
        }
        if (!$end) {
            return ['error' => 'Sorry, You must provide an end date.'];
        }
        $startUnix = Carbon::parse($start)->timestamp;
        $endUnix = Carbon::parse($end)->timestamp;
        if ($endUnix <= $startUnix) {
            return ['error' => 'Sorry, The end date can not be earlier than the start date.'];
        }
        if (!$content) {
            return ['error' => 'Sorry, You must provide the event content.'];
        }
        if (!$isPrivate) {
            if (!$calendar) {
                return ['error' => 'Sorry, You must select a calendar to place this event under.'];
            }
            if (in_array($calendar, ['followups', 'private'])) {
                return ['error' => 'Sorry, You can not place this public event under the Follow Ups or Private calendars.'];
            }
        }

        try {
            CalendarEvent::where('id', $row->id)->update([
                'title' => $title,
                'description' => $content,
                'calendar' => $isPrivate ? 'private' : $calendar,
                'is_private' => $isPrivate,
                'all_day' => $allDay,
                'start_date' => $startUnix,
                'end_date' => $endUnix,
            ]);

            CalendarEventUser::where('event_id', $row->id)->delete();
            if (!$isPrivate) {
                if ($users) {
                    foreach ($users as $user) {
                        CalendarEventUser::create([
                            'event_id' => $row->id,
                            'user_id' => $user
                        ]);
                    }
                }
            }
        } catch (\Exception $e) {
            return ['error' => sprintf("Error updating the event to the database. %s", $e->getMessage())];
        }

        return ['html' => 'Event Updated'];
    }

    /**
     * @param $data
     * @return array
     */
    public function addEvent($data)
    {
        $title = $data['event_title'];
        $start = $data['event_start_date'];
        $end = $data['event_end_date'];
        $allDay = $data['all_day'];
        $isPrivate = $data['event_private'];
        $calendar = $data['event_calendar_id'];
        $content = $data['event_content'];
        $users = isset($data['users']) ? $data['users'] : null;
        if (!$title) {
            return ['error' => 'Sorry, You must provide a title.'];
        }
        if (!$start) {
            return ['error' => 'Sorry, You must provide a start date.'];
        }
        if (!$end) {
            return ['error' => 'Sorry, You must provide an end date.'];
        }

        $startUnix = Carbon::parse($start)->timestamp;
        $endUnix = Carbon::parse($end)->timestamp;
        if ($endUnix <= $startUnix) {
            return ['error' => 'Sorry, The end date can not be earlier than the start date.'];
        }
        if (!$content) {
            return ['error' => 'Sorry, You must provide the event content.'];
        }
        if (!$isPrivate) {
            if (!$calendar) {
                return ['error' => 'Sorry, You must select a calendar to place this event under.'];
            }
            if (in_array($calendar, ['followups', 'private'])) {
                return ['error' => 'Sorry, You can not place this public event under the Follow Ups or Private calendars.'];
            }
        }
        try {
            $lastEvent = CalendarEvent::create([
                'title' => $title,
                'description' => $content,
                'calendar' => $isPrivate ? 'private' : $calendar,
                'is_private' => $isPrivate,
                'all_day' => $allDay,
                'start_date' => $startUnix,
                'end_date' => $endUnix,
                'created_date' => Carbon::now()->timestamp,
                'created_by' => getUserId(),
            ]);

            $newId = $lastEvent->id;

            if (!$isPrivate) {
                if ($users) {
                    foreach ($users as $key => $value) {
                        CalendarEventUser::create([
                            'event_id' => $newId,
                            'user_id' => $value
                        ]);
                    }
                }
            }
        } catch (\Exception $e) {
            return ['error' => sprintf("Error adding the event to the database. %s", $e->getMessage())];
        }

        return ['html' => 'Event Added.'];
    }

    /**
     * @param $id
     * @return array
     */
    public function deleteEvent($id)
    {
        try {
            CalendarEvent::where('id', $id)->delete();
            CalendarEventUser::where('event_id', $id)->delete();
            return ['html' => 'Event Deleted.'];
        } catch (\Exception $e) {
            return ['error' => sprintf("Error! %s", $e->getMessage())];
        }
    }
}
