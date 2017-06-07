<?php

namespace App\CoreBundle\Entity;

use Gedmo\Loggable\Entity\LogEntry as BaseLogEntry;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\AttributeOverrides({
 *      @ORM\AttributeOverride(name="objectClass",
 *          column=@ORM\Column(name="object_class", type="string", length=180)
 *      ),
 *      @ORM\AttributeOverride(name="username",
 *          column=@ORM\Column(name="username", nullable = true, length=180)
 *      )
 * })
 * @ORM\Table(
 *     name="ext_log_entries",
 *  indexes={
 *      @ORM\Index(name="log_class_lookup_idx", columns={"object_class"}),
 *      @ORM\Index(name="log_date_lookup_idx", columns={"logged_at"}),
 *      @ORM\Index(name="log_user_lookup_idx", columns={"username"}),
 *      @ORM\Index(name="log_version_lookup_idx", columns={"object_id", "object_class", "version"})
 *  }
 * )
 */
class LogEntry extends BaseLogEntry
{
}
