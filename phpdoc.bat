@ECHO OFF
REM home dir of php installation
cd C:\Program Files (x86)\iis express\PHP\v5.4
phpdoc.bat -d "C:\inetpub\wwwroot\teste" -t "C:\inetpub\wwwroot\teste\phpdoc" -i "C:\inetpub\wwwroot\teste\nbproject\*","C:\inetpub\wwwroot\teste\logs\*","C:\inetpub\wwwroot\teste\vendor\*" --title "DBManagerLogger Docs"