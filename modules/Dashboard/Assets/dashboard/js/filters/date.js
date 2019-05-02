import momentjs from "moment";

export function date(date, format = "MM/DD/Y hh:mm A") {
  return format == "fromnow"
    ? momentjs(date).fromNow()
    : momentjs(date).format(format);
}
