cp %windir%\System32\drivers\etc\hosts tmphost
sed -i '/'%1'/d' tmphost
mv tmphost %windir%\System32\drivers\etc\hosts