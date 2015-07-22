#!/bin/bash
/usr/bin/wget 'http://service.uwan.com/admin.php?c=TimerWorkOrder&a=Scan&doaction=ev&_verifycode=1307773192&_sign=67b7c13db16a06cc51b898c1d8bad759' -O /dev/null
/usr/bin/wget 'http://service.uwan.com/admin.php?c=TimerWorkOrder&a=Scan&doaction=new&_verifycode=1307773192&_sign=67b7c13db16a06cc51b898c1d8bad759' -O /dev/null
if [ $? -eq 0 ]
then
/usr/bin/wget 'http://service.uwan.com/admin.php?c=TimerWorkOrder&a=Scan&doaction=del&_verifycode=1307773192&_sign=67b7c13db16a06cc51b898c1d8bad759' -O /dev/null
fi
