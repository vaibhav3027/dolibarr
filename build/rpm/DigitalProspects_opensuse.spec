#---------------------------------------------------------
# Spec file to build a rpm file
#
# This is an example to build a rpm file. You can use this 
# file to build a package for your own distributions and 
# edit it if you need to match your rules.
# --------------------------------------------------------

Name: DigitalProspects
Version: __VERSION__
Release: __RELEASE__
Summary: ERP and CRM software for small and medium companies or foundations 
Summary(es): Software ERP y CRM para pequeñas y medianas empresas, asociaciones o autónomos
Summary(fr): Logiciel ERP & CRM de gestion de PME/PMI, auto-entrepreneurs ou associations
Summary(it): Programmo gestionale per piccole imprese, fondazioni e liberi professionisti

License: GPL-3.0+
#Packager: Laurent Destailleur (Eldy) <eldy@users.sourceforge.net>
Vendor: DigitalProspects dev team

URL: https://www.DigitalProspects.org
Source0: https://www.DigitalProspects.org/files/lastbuild/package_rpm_opensuse/%{name}-%{version}.tgz
Patch0: %{name}-forrpm.patch
BuildArch: noarch
BuildRoot: %{_tmppath}/%{name}-%{version}-build

Group: Productivity/Office/Management
Requires: apache2, apache2-mod_php5, php5 >= 5.3.0, php5-gd, php5-ldap, php5-imap, php5-mysql, php5-openssl, dejavu
Requires: mysql-community-server, mysql-community-server-client 
%if 0%{?suse_version}
BuildRequires: update-desktop-files fdupes
%endif

# Set yes to build test package, no for release (this disable need of /usr/bin/php not found by OpenSuse)
AutoReqProv: no


%description
An easy to use CRM & ERP open source/free software package for small  
and medium companies, foundations or freelances. It includes different 
features for Enterprise Resource Planning (ERP) and Customer Relationship 
Management (CRM) but also for different other activities.
DigitalProspects was designed to provide only features you need and be easy to 
use.

%description -l es
Un software ERP y CRM para pequeñas y medianas empresas, asociaciones
o autónomos. Incluye diferentes funcionalidades para la Planificación 
de Recursos Empresariales (ERP) y Gestión de la Relación con los
Clientes (CRM) así como para para otras diferentes actividades. 
DigitalProspects ha sido diseñado para suministrarle solamente las funcionalidades
que necesita y haciendo hincapié en su facilidad de uso.
    
%description -l fr
Logiciel ERP & CRM de gestion de PME/PMI, autoentrepreneurs, 
artisans ou associations. Il permet de gérer vos clients, prospect, 
fournisseurs, devis, factures, comptes bancaires, agenda, campagnes mailings
et bien d'autres choses dans une interface pensée pour la simplicité.

%description -l it
Un programmo gestionale per piccole e medie
imprese, fondazioni e liberi professionisti. Include varie funzionalità per
Enterprise Resource Planning e gestione dei clienti (CRM), ma anche ulteriori
attività. Progettato per poter fornire solo ciò di cui hai bisogno 
ed essere facile da usare.
Programmo web, progettato per poter fornire solo ciò di 
cui hai bisogno ed essere facile da usare.



#---- prep
%prep
%setup -q
%patch0 -p0 -b .patch


#---- build
%build
# Nothing to build


#---- install
%install
%{__rm} -rf $RPM_BUILD_ROOT

%{__mkdir} -p $RPM_BUILD_ROOT%{_sysconfdir}/%{name}
%{__install} -m 644 build/rpm/conf.php $RPM_BUILD_ROOT%{_sysconfdir}/%{name}/conf.php
%{__install} -m 644 build/rpm/httpd-DigitalProspects.conf $RPM_BUILD_ROOT%{_sysconfdir}/%{name}/apache.conf
%{__install} -m 644 build/rpm/file_contexts.DigitalProspects $RPM_BUILD_ROOT%{_sysconfdir}/%{name}/file_contexts.DigitalProspects
%{__install} -m 644 build/rpm/install.forced.php.opensuse $RPM_BUILD_ROOT%{_sysconfdir}/%{name}/install.forced.php

%{__mkdir} -p $RPM_BUILD_ROOT%{_datadir}/pixmaps
%{__install} -m 644 doc/images/appicon_64.png $RPM_BUILD_ROOT%{_datadir}/pixmaps/%{name}.png
%{__mkdir} -p $RPM_BUILD_ROOT%{_datadir}/applications
#desktop-file-install --delete-original --dir=$RPM_BUILD_ROOT%{_datadir}/applications build/rpm/%{name}.desktop
%{__install} -m 644 build/rpm/DigitalProspects.desktop $RPM_BUILD_ROOT%{_datadir}/applications/%{name}.desktop

%{__mkdir} -p $RPM_BUILD_ROOT%{_datadir}/%{name}/build/rpm
%{__mkdir} -p $RPM_BUILD_ROOT%{_datadir}/%{name}/build/tgz
%{__mkdir} -p $RPM_BUILD_ROOT%{_datadir}/%{name}/htdocs
%{__mkdir} -p $RPM_BUILD_ROOT%{_datadir}/%{name}/scripts
%{__cp} -pr build/rpm/*     $RPM_BUILD_ROOT%{_datadir}/%{name}/build/rpm
%{__cp} -pr build/tgz/*     $RPM_BUILD_ROOT%{_datadir}/%{name}/build/tgz
%{__cp} -pr htdocs  $RPM_BUILD_ROOT%{_datadir}/%{name}
%{__cp} -pr scripts $RPM_BUILD_ROOT%{_datadir}/%{name}
%{__rm} -rf $RPM_BUILD_ROOT%{_datadir}/%{name}/htdocs/includes/ckeditor/_source  
%{__rm} -rf $RPM_BUILD_ROOT%{_datadir}/%{name}/htdocs/includes/fonts

# Lang
echo "%defattr(0644, root, root, 0755)" > %{name}.lang
echo "%dir %{_datadir}/%{name}/htdocs/langs" >> %{name}.lang
for i in $RPM_BUILD_ROOT%{_datadir}/%{name}/htdocs/langs/*_*
do
  lang=$(basename $i)
  lang1=`expr substr $lang 1 2`; 
  lang2=`expr substr $lang 4 2 | tr "[:upper:]" "[:lower:]"`; 
  echo "%dir %{_datadir}/%{name}/htdocs/langs/${lang}" >> %{name}.lang
  if [ "$lang1" = "$lang2" ] ; then
    echo "%lang(${lang1}) %{_datadir}/%{name}/htdocs/langs/${lang}/*.lang"
  else
    echo "%lang(${lang}) %{_datadir}/%{name}/htdocs/langs/${lang}/*.lang"
  fi
done >>%{name}.lang

%if 0%{?suse_version}

# Enable this command to tag desktop file for suse
%suse_update_desktop_file DigitalProspects

# Enable this command to allow suse detection of duplicate files and create hardlinks instead
%fdupes $RPM_BUILD_ROOT%{_datadir}/%{name}/htdocs

%endif


#---- clean
%clean
%{__rm} -rf $RPM_BUILD_ROOT



#---- files
%files -f %{name}.lang

%defattr(0755, root, root, 0755)

%dir %_datadir/DigitalProspects

%dir %_datadir/DigitalProspects/scripts
%_datadir/DigitalProspects/scripts/*

%defattr(-, root, root, 0755)
%doc COPYING ChangeLog doc/index.html htdocs/langs/HOWTO-Translation.txt

%_datadir/pixmaps/DigitalProspects.png
%_datadir/applications/DigitalProspects.desktop

%dir %_datadir/DigitalProspects/build

%dir %_datadir/DigitalProspects/build/rpm
%_datadir/DigitalProspects/build/rpm/*

%dir %_datadir/DigitalProspects/build/tgz
%_datadir/DigitalProspects/build/tgz/*

%dir %_datadir/DigitalProspects/htdocs
%_datadir/DigitalProspects/htdocs/accountancy
%_datadir/DigitalProspects/htdocs/adherents
%_datadir/DigitalProspects/htdocs/admin
%_datadir/DigitalProspects/htdocs/api
%_datadir/DigitalProspects/htdocs/asset
%_datadir/DigitalProspects/htdocs/asterisk
%_datadir/DigitalProspects/htdocs/barcode
%_datadir/DigitalProspects/htdocs/blockedlog
%_datadir/DigitalProspects/htdocs/bookmarks
%_datadir/DigitalProspects/htdocs/bom
%_datadir/DigitalProspects/htdocs/cashdesk
%_datadir/DigitalProspects/htdocs/categories
%_datadir/DigitalProspects/htdocs/collab
%_datadir/DigitalProspects/htdocs/comm
%_datadir/DigitalProspects/htdocs/commande
%_datadir/DigitalProspects/htdocs/compta
%_datadir/DigitalProspects/htdocs/conf
%_datadir/DigitalProspects/htdocs/contact
%_datadir/DigitalProspects/htdocs/contrat
%_datadir/DigitalProspects/htdocs/core
%_datadir/DigitalProspects/htdocs/cron
%_datadir/DigitalProspects/htdocs/custom
%_datadir/DigitalProspects/htdocs/datapolicy
%_datadir/DigitalProspects/htdocs/dav
%_datadir/DigitalProspects/htdocs/debugbar
%_datadir/DigitalProspects/htdocs/don
%_datadir/DigitalProspects/htdocs/ecm
%_datadir/DigitalProspects/htdocs/emailcollector
%_datadir/DigitalProspects/htdocs/expedition
%_datadir/DigitalProspects/htdocs/expensereport
%_datadir/DigitalProspects/htdocs/exports
%_datadir/DigitalProspects/htdocs/externalsite
%_datadir/DigitalProspects/htdocs/fichinter
%_datadir/DigitalProspects/htdocs/fourn
%_datadir/DigitalProspects/htdocs/ftp
%_datadir/DigitalProspects/htdocs/holiday
%_datadir/DigitalProspects/htdocs/hrm
%_datadir/DigitalProspects/htdocs/imports
%_datadir/DigitalProspects/htdocs/includes
%_datadir/DigitalProspects/htdocs/install
%_datadir/DigitalProspects/htdocs/langs/HOWTO-Translation.txt
%_datadir/DigitalProspects/htdocs/livraison
%_datadir/DigitalProspects/htdocs/loan
%_datadir/DigitalProspects/htdocs/mailmanspip
%_datadir/DigitalProspects/htdocs/margin
%_datadir/DigitalProspects/htdocs/modulebuilder
%_datadir/DigitalProspects/htdocs/mrp
%_datadir/DigitalProspects/htdocs/multicurrency
%_datadir/DigitalProspects/htdocs/opensurvey
%_datadir/DigitalProspects/htdocs/paybox
%_datadir/DigitalProspects/htdocs/paypal
%_datadir/DigitalProspects/htdocs/printing
%_datadir/DigitalProspects/htdocs/product
%_datadir/DigitalProspects/htdocs/projet
%_datadir/DigitalProspects/htdocs/public
%_datadir/DigitalProspects/htdocs/reception
%_datadir/DigitalProspects/htdocs/resource
%_datadir/DigitalProspects/htdocs/salaries
%_datadir/DigitalProspects/htdocs/societe
%_datadir/DigitalProspects/htdocs/stripe
%_datadir/DigitalProspects/htdocs/supplier_proposal
%_datadir/DigitalProspects/htdocs/support
%_datadir/DigitalProspects/htdocs/theme
%_datadir/DigitalProspects/htdocs/takepos
%_datadir/DigitalProspects/htdocs/ticket
%_datadir/DigitalProspects/htdocs/user
%_datadir/DigitalProspects/htdocs/variants
%_datadir/DigitalProspects/htdocs/webservices
%_datadir/DigitalProspects/htdocs/website
%_datadir/DigitalProspects/htdocs/zapier
%_datadir/DigitalProspects/htdocs/*.ico
%_datadir/DigitalProspects/htdocs/*.patch
%_datadir/DigitalProspects/htdocs/*.php
%_datadir/DigitalProspects/htdocs/*.txt

%dir %{_sysconfdir}/DigitalProspects

%defattr(0664, root, www)
%config(noreplace) %{_sysconfdir}/DigitalProspects/conf.php
%config(noreplace) %{_sysconfdir}/DigitalProspects/apache.conf
%config(noreplace) %{_sysconfdir}/DigitalProspects/install.forced.php
%config(noreplace) %{_sysconfdir}/DigitalProspects/file_contexts.DigitalProspects



#---- post (after unzip during install)
%post

echo Run post script of packager DigitalProspects_opensuse.spec

# Define vars
export docdir="/var/lib/DigitalProspects/documents"
export apachelink="%{_sysconfdir}/apache2/conf.d/DigitalProspects.conf"
export apacheuser='wwwrun';
export apachegroup='www';

# Remove DigitalProspects install/upgrade lock file if it exists
%{__rm} -f $docdir/install.lock

# Create empty directory for uploaded files and generated documents 
echo Create document directory $docdir
%{__mkdir} -p $docdir

# Set correct owner on config files
%{__chown} -R root:$apachegroup /etc/DigitalProspects/*

# If a conf already exists and its content was already completed by installer
export config=%{_sysconfdir}/DigitalProspects/conf.php
if [ -s $config ] && grep -q "File generated by" $config
then 
  # File already exist. We add params not found.
  echo Add new params to overwrite path to use shared libraries/fonts
  grep -q -c "DigitalProspects_lib_ADODB_PATH" $config     || [ ! -d "/usr/share/php/adodb" ]  || echo "<?php \$DigitalProspects_lib_ADODB_PATH='/usr/share/php/adodb'; ?>" >> $config
  grep -q -c "DigitalProspects_lib_FPDI_PATH" $config      || [ ! -d "/usr/share/php/fpdi" ]   || echo "<?php \$DigitalProspects_lib_FPDI_PATH='/usr/share/php/fpdi'; ?>" >> $config
  #grep -q -c "DigitalProspects_lib_GEOIP_PATH" $config    || echo "<?php \$DigitalProspects_lib_GEOIP_PATH=''; ?>" >> $config
  grep -q -c "DigitalProspects_lib_NUSOAP_PATH" $config    || [ ! -d "/usr/share/php/nusoap" ] || echo "<?php \$DigitalProspects_lib_NUSOAP_PATH='/usr/share/php/nusoap'; ?>" >> $config
  grep -q -c "DigitalProspects_lib_ODTPHP_PATHTOPCLZIP" $config || [ ! -d "/usr/share/php/libphp-pclzip" ]  || echo "<?php \$DigitalProspects_lib_ODTPHP_PATHTOPCLZIP='/usr/share/php/libphp-pclzip'; ?>" >> $config
  #grep -q -c "DigitalProspects_lib_PHPEXCEL_PATH" $config || echo "<?php \$DigitalProspects_lib_PHPEXCEL_PATH=''; ?>" >> $config
  #grep -q -c "DigitalProspects_lib_TCPDF_PATH" $config    || echo "<?php \$DigitalProspects_lib_TCPDF_PATH=''; ?>" >> $config
  grep -q -c "DigitalProspects_js_CKEDITOR" $config        || [ ! -d "/usr/share/javascript/ckeditor" ]  || echo "<?php \$DigitalProspects_js_CKEDITOR='/javascript/ckeditor'; ?>" >> $config
  grep -q -c "DigitalProspects_js_JQUERY" $config          || [ ! -d "/usr/share/javascript/jquery" ]    || echo "<?php \$DigitalProspects_js_JQUERY='/javascript/jquery'; ?>" >> $config
  grep -q -c "DigitalProspects_js_JQUERY_UI" $config       || [ ! -d "/usr/share/javascript/jquery-ui" ] || echo "<?php \$DigitalProspects_js_JQUERY_UI='/javascript/jquery-ui'; ?>" >> $config
  grep -q -c "DigitalProspects_js_JQUERY_FLOT" $config     || [ ! -d "/usr/share/javascript/flot" ]      || echo "<?php \$DigitalProspects_js_JQUERY_FLOT='/javascript/flot'; ?>" >> $config
  grep -q -c "DigitalProspects_font_DOL_DEFAULT_TTF_BOLD" $config || echo "<?php \$DigitalProspects_font_DOL_DEFAULT_TTF_BOLD='/usr/share/fonts/truetype/DejaVuSans-Bold.ttf'; ?>" >> $config      
fi

# Create a config link DigitalProspects.conf
if [ ! -L $apachelink ]; then
  apachelinkdir=`dirname $apachelink`
  if [ -d $apachelinkdir ]; then
    echo Create DigitalProspects web server config link from %{_sysconfdir}/DigitalProspects/apache.conf to $apachelink
    ln -fs %{_sysconfdir}/DigitalProspects/apache.conf $apachelink
  else
    echo Do not create link $apachelink - web server conf dir $apachelinkdir not found. web server package may not be installed
  fi
fi

echo Set permission to $apacheuser:$apachegroup on /var/lib/DigitalProspects
%{__chown} -R $apacheuser:$apachegroup /var/lib/DigitalProspects
%{__chmod} -R o-w /var/lib/DigitalProspects

# Restart web server
echo Restart web server
if [ -f %{_sysconfdir}/init.d/httpd ]; then
  %{_sysconfdir}/init.d/httpd restart
fi
if [ -f %{_sysconfdir}/init.d/apache2 ]; then
  %{_sysconfdir}/init.d/apache2 restart
fi

# Restart mysql
echo Restart mysql
if [ -f /etc/init.d/mysqld ]; then
  /sbin/service mysqld restart
fi
if [ -f /etc/init.d/mysql ]; then
  /sbin/service mysql restart
fi

# Show result
echo
echo "----- DigitalProspects %version-%release - (c) DigitalProspects dev team -----"
echo "DigitalProspects files are now installed (into /usr/share/DigitalProspects)."
echo "To finish installation and use DigitalProspects, click on the menu" 
echo "entry DigitalProspects ERP-CRM or call the following page from your"
echo "web browser:"  
echo "http://localhost/DigitalProspects/"
echo "-------------------------------------------------------"
echo


#---- postun (after upgrade or uninstall)
%postun

if [ "x$1" = "x0" ] ;
then
  # Remove
  echo "Removed package"
  
  # Define vars
  export apachelink="%{_sysconfdir}/apache2/conf.d/DigitalProspects.conf"
  
  # Remove apache link
  if [ -L $apachelink ] ;
  then
    echo "Delete apache config link for DigitalProspects ($apachelink)"
    %{__rm} -f $apachelink
    status=purge
  fi
  
  # Restart web servers if required
  if [ "x$status" = "xpurge" ] ;
  then
    # Restart web server
    echo Restart web server
    if [ -f %{_sysconfdir}/init.d/httpd ]; then
      %{_sysconfdir}/init.d/httpd restart
    fi
    if [ -f %{_sysconfdir}/init.d/apache2 ]; then
      %{_sysconfdir}/init.d/apache2 restart
    fi
  fi
else
  # Upgrade
  echo "No remove action done (this is an upgrade)"
fi


# version x.y.z-0.1.a for alpha, x.y.z-0.2.b for beta, x.y.z-0.3 for release
%changelog
__CHANGELOGSTRING__
