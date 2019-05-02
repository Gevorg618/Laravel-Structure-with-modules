<?php

function mailSubject($subject)
{
  return sprintf("[%s] %s", setting('company_name'), $subject);
}